<?php

namespace Tests\Feature;

use App\Models\VisitorPresence;
use App\Services\AnalyticsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class VisitorPresenceTest extends TestCase
{
    use RefreshDatabase;

    public function test_leave_presence_removes_live_visitor(): void
    {
        $this->withSession(['analytics_session_id' => 'live-session-1']);

        VisitorPresence::create([
            'session_id' => 'live-session-1',
            'current_path' => '/',
            'last_seen_at' => now(),
            'first_seen_at' => now(),
        ]);

        $this->assertSame(1, app(AnalyticsService::class)->liveVisitorCount());

        $this->post('/analytics/presence/leave')->assertNoContent();

        $this->assertSame(0, app(AnalyticsService::class)->liveVisitorCount());
        $this->assertDatabaseMissing('visitor_presence', ['session_id' => 'live-session-1']);
    }

    public function test_stale_presence_is_not_counted_live(): void
    {
        VisitorPresence::create([
            'session_id' => 'stale-session',
            'current_path' => '/',
            'last_seen_at' => now()->subMinutes(2),
            'first_seen_at' => now()->subMinutes(3),
        ]);

        $this->assertSame(0, app(AnalyticsService::class)->liveVisitorCount());
    }

    public function test_bots_are_not_stored_as_live(): void
    {
        $request = Request::create('/', 'GET');
        $request->headers->set('User-Agent', 'Mozilla/5.0 (compatible; Googlebot/2.1)');
        $request->setLaravelSession($this->app['session']->driver());

        app(AnalyticsService::class)->touchPresence($request);

        $this->assertSame(0, app(AnalyticsService::class)->liveVisitorCount());
    }

    public function test_heartbeat_creates_missing_presence_and_refreshes_it(): void
    {
        $this->withSession(['analytics_session_id' => 'hb-session']);

        $this->post('/analytics/presence')->assertNoContent();

        $this->assertSame(1, app(AnalyticsService::class)->liveVisitorCount());
        $this->assertDatabaseHas('visitor_presence', ['session_id' => 'hb-session']);

        VisitorPresence::where('session_id', 'hb-session')->update([
            'last_seen_at' => now()->subSeconds(20),
        ]);

        $this->post('/analytics/presence')->assertNoContent();

        $this->assertSame(1, app(AnalyticsService::class)->liveVisitorCount());
        $this->assertTrue(
            VisitorPresence::where('session_id', 'hb-session')->first()->last_seen_at->gte(now()->subSeconds(5))
        );
    }
}
