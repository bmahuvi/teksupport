<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketDeleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public string $ticketId,
        public string $ticketUid,
        public mixed  $deletedBy
    )
    {
    }
}
