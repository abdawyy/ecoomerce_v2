<?php

namespace App\Support;

class OrderStatus
{
    public const PENDING = 'Pending';

    public const PROCESSING = 'Processing';

    public const COMPLETED = 'Completed';

    public const CANCELLED = 'Cancelled';

    public static function all(): array
    {
        return [self::PENDING, self::PROCESSING, self::COMPLETED, self::CANCELLED];
    }

    public static function normalize(?string $status): string
    {
        return match (strtolower(trim((string) $status))) {
            'processing' => self::PROCESSING,
            'completed' => self::COMPLETED,
            'cancelled', 'canceled' => self::CANCELLED,
            default => self::PENDING,
        };
    }

    public static function isCancelled(?string $status): bool
    {
        return self::normalize($status) === self::CANCELLED;
    }

    public static function isCompleted(?string $status): bool
    {
        return self::normalize($status) === self::COMPLETED;
    }

    public static function completedValues(): array
    {
        return [self::COMPLETED, 'completed'];
    }

    public static function pendingValues(): array
    {
        return [self::PENDING, 'pending', self::PROCESSING, 'processing'];
    }

    public static function aliases(?string $status): array
    {
        return match (self::normalize($status)) {
            self::CANCELLED => [self::CANCELLED, 'cancelled', 'canceled'],
            self::COMPLETED => [self::COMPLETED, 'completed'],
            self::PROCESSING => [self::PROCESSING, 'processing'],
            default => [self::PENDING, 'pending'],
        };
    }

    public static function listFilterValues(?string $status): array
    {
        if (self::normalize($status) === self::PENDING) {
            return self::pendingValues();
        }

        return self::aliases($status);
    }

    public static function validationRule(): string
    {
        return 'required|string|in:Pending,Processing,Completed,Cancelled,pending,processing,completed,cancelled,canceled';
    }
}
