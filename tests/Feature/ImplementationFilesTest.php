<?php

namespace Tests\Feature;

use App\Support\ImplementationManifest;
use Tests\TestCase;

class ImplementationFilesTest extends TestCase
{
    public function test_all_implementation_files_exist(): void
    {
        $missing = [];

        foreach (ImplementationManifest::requiredFiles() as $path => $label) {
            if (! is_file(base_path($path))) {
                $missing[] = "{$path} ({$label})";
            }
        }

        $this->assertEmpty(
            $missing,
            "Missing implementation files:\n".implode("\n", $missing)
        );
    }

    public function test_key_routes_are_registered(): void
    {
        foreach (ImplementationManifest::requiredRoutes() as $name) {
            $this->assertTrue(\Illuminate\Support\Facades\Route::has($name), "Route [{$name}] is not registered.");
        }
    }
}
