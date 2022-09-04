<?php

namespace App\Listeners;

use App\Events\OrderCompleted;
use App\Models\Order;

class DecreaseCreditsForUser
{
    public function __construct()
    {
        //
    }

    public function handle(OrderCompleted $event): void
    {
        if ($event->order->user_id === null) {
            return;
        }

        $credits = $this->calculateCredits($event->order);

        // Remove the credits from the user
        $event->order->user()->decrement('credits', $credits);
    }

    private function calculateCredits(Order $order): int
    {
        $currentCredits = $order->user->credits;

        $credits = $currentCredits - $order->total;

        return $credits < 0 ? $order->user->credits : $credits;
    }
}
