<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class PublicUpload
{
    public const ALLOWED_DIRECTORIES = ['uploads', 'branding', 'guides'];

    public function store(UploadedFile $file, string $directory, ?string $fileName = null): string
    {
        if (! $file->isValid()) {
            throw new \RuntimeException(__('products.images_too_large'));
        }

        $directory = $this->normalizeDirectory($directory);
        $fileName = $fileName ? $this->safeFileName($fileName) : $this->uniqueName($file);
        $relative = $directory.'/'.$fileName;
        $errors = [];
        $written = null;

        foreach ($this->candidateDirectories($directory) as $dir) {
            if (! $this->ensureWritable($dir, $errors)) {
                continue;
            }

            $target = rtrim($dir, '/\\').DIRECTORY_SEPARATOR.$fileName;

            try {
                $file->move($dir, $fileName);
                if (is_file($target)) {
                    $written = $target;
                    break;
                }
                $errors[] = $dir.': move finished without a file';
            } catch (\Throwable $e) {
                $errors[] = $dir.': '.$e->getMessage();
            }
        }

        if ($written === null) {
            Log::warning('Public upload failed', [
                'directory' => $directory,
                'errors' => $errors,
                'php' => error_get_last(),
            ]);

            throw new \RuntimeException(__('products.images_save_failed'));
        }

        $this->mirrorToPublic($written, $relative);

        return $relative;
    }

    public function delete(?string $relative): bool
    {
        $relative = $this->safeRelative($relative);
        if ($relative === null) {
            return false;
        }

        $deleted = false;
        $roots = $this->roots();

        foreach ($this->candidateFiles($relative) as $path) {
            if (! is_file($path)) {
                continue;
            }

            $real = realpath($path);
            if ($real === false || ! $this->withinRoots($real, $roots)) {
                continue;
            }

            if (@unlink($real)) {
                $deleted = true;
            }
        }

        return $deleted;
    }

    public function locate(?string $relative): ?string
    {
        $relative = $this->safeRelative($relative);
        if ($relative === null) {
            return null;
        }

        $roots = $this->roots();

        foreach ($this->candidateFiles($relative) as $path) {
            if (! is_file($path)) {
                continue;
            }

            $real = realpath($path);
            if ($real !== false && $this->withinRoots($real, $roots)) {
                return $real;
            }
        }

        return null;
    }

    protected function normalizeDirectory(string $directory): string
    {
        $directory = trim(str_replace('\\', '/', $directory), '/');
        if (! in_array($directory, self::ALLOWED_DIRECTORIES, true)) {
            throw new \InvalidArgumentException('Upload directory is not allowed.');
        }

        return $directory;
    }

    protected function uniqueName(UploadedFile $file): string
    {
        $extension = strtolower((string) ($file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg'));
        $extension = preg_replace('/[^a-z0-9]/', '', $extension) ?: 'jpg';

        return time().'-'.uniqid().'.'.$extension;
    }

    protected function safeFileName(string $fileName): string
    {
        $fileName = basename(str_replace('\\', '/', $fileName));
        $fileName = preg_replace('/[^A-Za-z0-9._-]/', '', $fileName) ?? '';
        if ($fileName === '' || $fileName === '.' || $fileName === '..' || ! str_contains($fileName, '.')) {
            throw new \InvalidArgumentException('Upload file name is not allowed.');
        }

        return $fileName;
    }

    protected function safeRelative(?string $path): ?string
    {
        if (! is_string($path) || $path === '' || str_contains($path, "\0")) {
            return null;
        }

        $path = str_replace('\\', '/', $path);
        $path = ltrim($path, '/');
        if ($path === '' || str_contains($path, '..')) {
            return null;
        }

        $segments = explode('/', $path);
        if (count($segments) < 2 || ! in_array($segments[0], self::ALLOWED_DIRECTORIES, true)) {
            return null;
        }

        foreach ($segments as $segment) {
            if ($segment === '' || $segment === '.' || $segment === '..') {
                return null;
            }
        }

        return implode('/', $segments);
    }

    protected function candidateDirectories(string $directory): array
    {
        return [
            storage_path('app/public/'.$directory),
            storage_path('app/'.$directory),
            storage_path('framework/'.$directory),
            public_path('storage/'.$directory),
        ];
    }

    protected function candidateFiles(string $relative): array
    {
        return [
            storage_path('app/public/'.$relative),
            storage_path('app/'.$relative),
            storage_path('framework/'.$relative),
            public_path('storage/'.$relative),
        ];
    }

    protected function roots(): array
    {
        return [
            storage_path('app/public'),
            storage_path('app'),
            storage_path('framework'),
            public_path('storage'),
        ];
    }

    protected function ensureWritable(string $dir, array &$errors): bool
    {
        if (! is_dir($dir) && ! @mkdir($dir, 0775, true) && ! is_dir($dir)) {
            $errors[] = $dir.': '.($this->lastError() ?: 'mkdir failed');

            return false;
        }

        if (! is_writable($dir)) {
            $errors[] = $dir.': not writable';

            return false;
        }

        return true;
    }

    protected function mirrorToPublic(string $written, string $relative): void
    {
        $publicPath = public_path('storage/'.$relative);
        $writtenReal = realpath($written);
        $publicReal = is_file($publicPath) ? realpath($publicPath) : false;
        if ($writtenReal && $publicReal && $this->pathEquals($this->normalizePath($writtenReal), $this->normalizePath($publicReal))) {
            return;
        }

        if (is_file($publicPath)) {
            return;
        }

        $publicDir = dirname($publicPath);
        if (! is_dir($publicDir) && ! @mkdir($publicDir, 0775, true)) {
            return;
        }

        if (! is_dir($publicDir) || ! is_writable($publicDir)) {
            return;
        }

        @copy($written, $publicPath);
    }

    protected function withinRoots(string $real, array $roots): bool
    {
        $real = $this->normalizePath($real);

        foreach ($roots as $root) {
            $rootReal = realpath($root);
            if ($rootReal === false) {
                continue;
            }

            $rootReal = $this->normalizePath($rootReal);
            if ($this->pathEquals($real, $rootReal) || $this->pathStartsWith($real, $rootReal.'/')) {
                return true;
            }
        }

        return false;
    }

    protected function normalizePath(string $path): string
    {
        return rtrim(str_replace('\\', '/', $path), '/');
    }

    protected function pathEquals(string $left, string $right): bool
    {
        return PHP_OS_FAMILY === 'Windows' ? strcasecmp($left, $right) === 0 : $left === $right;
    }

    protected function pathStartsWith(string $path, string $prefix): bool
    {
        if (PHP_OS_FAMILY === 'Windows') {
            return stripos($path, $prefix) === 0;
        }

        return str_starts_with($path, $prefix);
    }

    protected function lastError(): string
    {
        $error = error_get_last();

        return is_array($error) ? (string) ($error['message'] ?? '') : '';
    }
}
