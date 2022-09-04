<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\ConstantArrayable;

final class WebhookType
{
    use ConstantArrayable;

    public const ORDER_PAID = 'order-paid';

    public const ORDER_CANCELLED = 'order-cancelled';

    public const CREATE_CUSTOMER = 'create-customer';
}
