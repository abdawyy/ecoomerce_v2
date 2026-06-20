<?php

namespace App\Services;

use App\Models\GuestUser;
use App\Models\Message;
use App\Models\orders;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class AdminNotificationService
{
    public function recentDays(): int
    {
        return 7;
    }

    public function counts(): array
    {
        $since = now()->subDays($this->recentDays());

        return [
            'pending_orders' => orders::whereIn('status', ['Pending', 'pending', 'Processing'])->count(),
            'unread_messages' => $this->unreadMessagesCount(),
            'new_users' => User::where('created_at', '>=', $since)->count(),
            'new_guests' => GuestUser::where('created_at', '>=', $since)->count(),
        ];
    }

    public function unreadMessagesCount(): int
    {
        if (! Schema::hasTable('messages')) {
            return 0;
        }

        if (Schema::hasColumn('messages', 'read_at')) {
            return Message::whereNull('read_at')->count();
        }

        return Message::where('created_at', '>=', now()->subDays($this->recentDays()))->count();
    }

    public function markMessagesRead(): void
    {
        if (Schema::hasTable('messages') && Schema::hasColumn('messages', 'read_at')) {
            Message::whereNull('read_at')->update(['read_at' => now()]);
        }
    }

    public function recentUnreadMessages(int $limit = 5)
    {
        if (! Schema::hasTable('messages')) {
            return collect();
        }

        $query = Message::query()->orderByDesc('created_at');

        if (Schema::hasColumn('messages', 'read_at')) {
            $query->whereNull('read_at');
        } else {
            $query->where('created_at', '>=', now()->subDays($this->recentDays()));
        }

        return $query->limit($limit)->get();
    }

    public function recentUsers(int $limit = 5)
    {
        return User::where('created_at', '>=', now()->subDays($this->recentDays()))
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    public function recentGuests(int $limit = 5)
    {
        return GuestUser::where('created_at', '>=', now()->subDays($this->recentDays()))
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    public function activityFeed(int $limit = 15): \Illuminate\Support\Collection
    {
        $items = collect();

        foreach (orders::with(['user', 'guestUser'])->orderByDesc('created_at')->limit(8)->get() as $order) {
            $customer = $order->user?->name ?? $order->guestUser?->name ?? __('dashboard.guest');
            $items->push([
                'type' => 'order',
                'at' => $order->created_at,
                'title' => __('dashboard.activity_order', ['id' => $order->id]),
                'subtitle' => $customer.' · '.number_format($order->total_amount, 0).' '.__('dashboard.currency'),
                'url' => route('order.show', $order->id),
                'icon' => 'bi-cart3',
                'variant' => 'warning',
            ]);
        }

        foreach ($this->recentUnreadMessages(8) as $message) {
            $items->push([
                'type' => 'message',
                'at' => $message->created_at,
                'title' => __('dashboard.activity_message', ['name' => $message->name]),
                'subtitle' => \Illuminate\Support\Str::limit($message->message, 60),
                'url' => route('admin.contact.list'),
                'icon' => 'bi-envelope',
                'variant' => 'danger',
            ]);
        }

        foreach ($this->recentUsers(5) as $user) {
            $items->push([
                'type' => 'user',
                'at' => $user->created_at,
                'title' => __('dashboard.activity_user', ['name' => $user->name]),
                'subtitle' => $user->email,
                'url' => route('user.show', $user->id),
                'icon' => 'bi-person-plus',
                'variant' => 'info',
            ]);
        }

        foreach ($this->recentGuests(5) as $guest) {
            $items->push([
                'type' => 'guest',
                'at' => $guest->created_at,
                'title' => __('dashboard.activity_guest', ['name' => $guest->name]),
                'subtitle' => $guest->email,
                'url' => route('admin.guest.show', $guest->id),
                'icon' => 'bi-person-badge',
                'variant' => 'info',
            ]);
        }

        return $items->filter(fn ($item) => $item['at'] !== null)
            ->sortByDesc('at')
            ->take($limit)
            ->values();
    }
}
