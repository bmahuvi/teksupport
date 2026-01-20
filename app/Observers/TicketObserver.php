<?php

namespace App\Observers;

use App\Enums\TicketPriority;
use App\Models\Ticket;
use App\Models\TicketStatus;
use Illuminate\Support\Str;


class TicketObserver
{
    /**
     * Handle the Ticket "created" event.
     */
    public function created(Ticket $ticket): void
    {
        //
    }

    /**
     * Handle the Ticket "updated" event.
     */
    public function updated(Ticket $ticket): void
    {
        //
    }

    /**
     * Handle the Ticket "deleted" event.
     */
    public function deleted(Ticket $ticket): void
    {
        //
    }

    /**
     * Handle the Ticket "restored" event.
     */
    public function restored(Ticket $ticket): void
    {
        //
    }

    /**
     * Handle the Ticket "force deleted" event.
     */
    public function forceDeleted(Ticket $ticket): void
    {
        //
    }

    public function creating(Ticket $ticket): void
    {
        if (empty($ticket->ticket_number)) {
            $ticket->ticket_number = $this->generateTicketNumber($ticket->category->initial);
        }

        if (empty($ticket->ticket_ulid)) {
            $ticket->ticket_ulid = Str::ulid();
        }

        if (empty($ticket->ticket_status_id)) {
            $defaultStatus = TicketStatus::where('is_default_for_new', true)->first();

            if ($defaultStatus) {
                $ticket->ticket_status_id = $defaultStatus->id;
            }
        }

        if (empty($ticket->priority)) {
            $ticket->priority = TicketPriority::LOW;
        }
        if ($ticket->category) {
            if ($ticket->category->requires_approval) {
                $ticket->requires_approval = true;
            }
        }
    }

    protected function generateTicketNumber($prefix): string
    {
        $date = now()->format('ymd');
        $random = strtoupper(Str::random(6));
        $format = '{PREFIX}-{DATE}-{RAND}';

        $ticket_number = str_replace(['{PREFIX}', '{DATE}', '{RAND}'], [$prefix, $date, $random], $format);

        if (Ticket::where('ticket_number', $ticket_number)->exists()) {
            $this->generateTicketNumber($prefix);
        }

        return $ticket_number;
    }

    public function updating(Ticket $ticket): void
    {
        //
    }
}
