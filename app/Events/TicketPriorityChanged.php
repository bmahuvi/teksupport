<?php

namespace App\Events;

use App\Enums\TicketPriority;
use App\Models\Ticket;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketPriorityChanged
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Ticket         $ticket,
        public TicketPriority $oldPriority,
        public TicketPriority $newPriority,
        public mixed          $changedBy
    )
    {
    }
}
