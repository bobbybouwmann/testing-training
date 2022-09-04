<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\ConstantArrayable;

final class OrderStatus
{
    use ConstantArrayable;

    public const INITIATED = 'initiated';

    public const PENDING = 'pending';

    public const PAID = 'paid';

    public const CANCELLED = 'cancelled';

    public const FAILED = 'failed';

    public static function isPending(string $status): bool
    {
        return in_array($status, [self::INITIATED, self::PENDING], true);
    }

    public static function isPaid(string $status): bool
    {
        return $status === self::PAID;
    }

    public static function isFailed(string $status): bool
    {
        return in_array($status, [self::CANCELLED, self::FAILED], true);
    }
}
