<?php

namespace App\Dto;

final class Progress
{
    public function __construct(
        public int $total,
        public int $current
    ) {
    }

    public function percentage(): int
    {
        if ($this->total === 0) {
            return 0;
        }

        $percentage = (int) round($this->current / $this->total * 100);

        if ($percentage > 100) {
            return 100;
        }

        return $percentage;
    }
}
