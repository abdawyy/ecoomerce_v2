<?php

namespace Tests\Unit;

use App\Services\PublicUpload;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class PublicUploadTest extends TestCase
{
    public function test_it_stores_with_move_and_deletes_every_copy(): void
    {
        $uploads = new PublicUpload();
        $relative = $uploads->store(
            UploadedFile::fake()->create('shirt.jpg', 10, 'image/jpeg'),
            'uploads'
        );

        try {
            $this->assertMatchesRegularExpression('#^uploads/\d+-[a-z0-9]+\.jpg$#', $relative);
            $located = $uploads->locate($relative);
            $this->assertNotNull($located);
            $this->assertFileExists(storage_path('app/public/'.$relative));

            $response = $this->get('/storage/'.$relative);
            $response->assertOk();

            $this->assertTrue($uploads->delete($relative));
            $this->assertNull($uploads->locate($relative));
            $this->assertFileDoesNotExist(storage_path('app/public/'.$relative));
            $this->assertFileDoesNotExist(public_path('storage/'.$relative));
        } finally {
            $uploads->delete($relative);
        }
    }

    public function test_it_rejects_paths_outside_the_upload_directories(): void
    {
        $uploads = new PublicUpload();

        $this->assertNull($uploads->locate('../.env'));
        $this->assertNull($uploads->locate('uploads/../../.env'));
        $this->assertNull($uploads->locate('storage/app/.env'));
        $this->assertFalse($uploads->delete('../.env'));
    }
}
