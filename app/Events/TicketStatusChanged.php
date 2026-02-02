<?php

namespace App\Events;

use App\Models\Ticket;
use App\Models\TicketStatus;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketStatusChanged
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Ticket        $ticket,
        public ?TicketStatus $oldStatus,
        public TicketStatus  $newStatus,
        public mixed         $changedBy
    )
    {
    }
}
