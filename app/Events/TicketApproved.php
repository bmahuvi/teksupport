<?php

namespace App\Events;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketApproved
{
    use Dispatchable, SerializesModels, InteractsWithSockets;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Ticket $ticket,
        public ?User  $user,
        public string $viewTicketUrl)
    {
    }


}
