<?php

namespace App\Listeners;

use App\Enums\OrderStatus;
use App\Events\OrderCompleted;
use App\Notifications\OrderCompletedNotification;

class SendOrderCompletedNotification
{
    public function __construct()
    {
        //
    }

    public function handle(OrderCompleted $event): void
    {
        if (! OrderStatus::isPaid($event->order->status)) {
            return;
        }

        if ($event->order->user_id === null) {
            return;
        }

        $event->order->user->notify(new OrderCompletedNotification($event->order));
    }
}
