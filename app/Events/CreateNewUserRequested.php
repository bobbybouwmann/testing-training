<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreateNewUserRequested
{
    use Dispatchable, SerializesModels;

    /**
     * @param  array<string, mixed>  $fields
     */
    public function __construct(public array $fields)
    {
    }
}
