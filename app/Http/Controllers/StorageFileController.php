<?php

namespace App\Http\Controllers;

use App\Services\PublicUpload;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class StorageFileController extends Controller
{
    public function show(PublicUpload $uploads, string $path): BinaryFileResponse
    {
        $full = $uploads->locate($path);
        if ($full === null) {
            abort(404);
        }

        return response()->file($full);
    }
}
