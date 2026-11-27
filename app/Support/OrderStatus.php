<?php

namespace App\Support;

class OrderStatus
{
    public const PENDING = 'Pending';

    public const PROCESSING = 'Processing';

    public const OUT_FOR_DELIVERY = 'Out for delivery';

    public const COMPLETED = 'Completed';

    public const CANCELLED = 'Cancelled';

    public static function all(): array
    {
        return [
            self::PENDING,
            self::PROCESSING,
            self::OUT_FOR_DELIVERY,
            self::COMPLETED,
            self::CANCELLED,
        ];
    }

    public static function normalize(?string $status): string
    {
        $value = strtolower(trim((string) $status));
        $value = str_replace(['-', '_'], ' ', $value);
        $value = preg_replace('/\s+/', ' ', $value) ?? $value;

        return match ($value) {
            'processing' => self::PROCESSING,
            'out for delivery', 'outfordelivery' => self::OUT_FOR_DELIVERY,
            'completed', 'complete', 'complate' => self::COMPLETED,
            'cancelled', 'canceled', 'cancel' => self::CANCELLED,
            default => self::PENDING,
        };
    }

    public static function mailKey(?string $status): string
    {
        return match (self::normalize($status)) {
            self::OUT_FOR_DELIVERY => 'Out_for_delivery',
            self::PROCESSING => 'Processing',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
            default => 'Pending',
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
        return [self::COMPLETED, 'completed', 'complete', 'complate'];
    }

    public static function pendingValues(): array
    {
        return [
            self::PENDING, 'pending',
            self::PROCESSING, 'processing',
            self::OUT_FOR_DELIVERY, 'out for delivery', 'out_for_delivery',
        ];
    }

    public static function aliases(?string $status): array
    {
        return match (self::normalize($status)) {
            self::CANCELLED => [self::CANCELLED, 'cancelled', 'canceled', 'cancel'],
            self::COMPLETED => [self::COMPLETED, 'completed', 'complete', 'complate'],
            self::PROCESSING => [self::PROCESSING, 'processing'],
            self::OUT_FOR_DELIVERY => [self::OUT_FOR_DELIVERY, 'out for delivery', 'out_for_delivery'],
            default => [self::PENDING, 'pending'],
        };
    }

    public static function listFilterValues(?string $status): array
    {
        return self::aliases($status);
    }

    public static function validationRule(): string
    {
        return 'required|string|in:Pending,Processing,Out for delivery,Completed,Cancelled,pending,processing,out for delivery,out_for_delivery,completed,complete,complate,cancelled,canceled,cancel';
    }
}
