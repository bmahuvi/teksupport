<?php

namespace App\Events;

use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketReplyAdded
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Ticket      $ticket,
        public TicketReply $reply
    )
    {
    }
}
