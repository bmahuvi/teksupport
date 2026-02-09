<?php

namespace App\Observers;

use App\Enums\TicketPriority;
use App\Events\TicketAssigned;
use App\Events\TicketClosed;
use App\Events\TicketCreated;
use App\Events\TicketDeleted;
use App\Events\TicketPriorityChanged;
use App\Events\TicketStatusChanged;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\User;
use App\Notifications\TicketClosedNotification;
use App\Notifications\TicketPriorityChangedNotification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class TicketObserver
{
    /**
     * Handle the Ticket "created" event.
     */
    public function created(Ticket $ticket): void
    {
        $ticket->activities()->create([
            'user_id' => auth()->id(),
            'description' => 'Ticket was created',
        ]);

        event(new TicketCreated($ticket, auth()->user()));
    }

    /**
     * Handle the Ticket "updated" event.
     */
    public function updated(Ticket $ticket): void
    {
        if ($ticket->wasChanged('assigned_to')) {
            $oldAssigneeId = $ticket->getOriginal('assigned_to');
            $newAssigneeId = $ticket->assigned_to;

            event(new TicketAssigned($ticket, $oldAssigneeId, $newAssigneeId, auth()->user()));
        }

        if ($ticket->wasChanged('ticket_status_id')) {
            $oldStatusId = $ticket->getOriginal('ticket_status_id');
            $newStatusId = $ticket->ticket_status_id;

            $oldStatus = $oldStatusId ? TicketStatus::find($oldStatusId) : null;
            $newStatus = TicketStatus::find($newStatusId);

            event(new TicketStatusChanged($ticket, $oldStatus, $newStatus, auth()->user()));

            if ($newStatus && $newStatus->is_closing_status) {
                event(new TicketClosed($ticket, auth()->user()));

                $ticket->createdBy->notify(new TicketClosedNotification($ticket, $oldStatus->name));
            }
        }

        if ($ticket->wasChanged('priority')) {
            $oldPriorityVal = $ticket->getRawOriginal('priority');
            $oldPriority = TicketPriority::tryFrom($oldPriorityVal) ?? $oldPriorityVal;
            $newPriority = $ticket->priority;

            event(new TicketPriorityChanged($ticket, $oldPriority, $newPriority, auth()->user()));
            $ticket->createdBy->notify(new TicketPriorityChangedNotification($ticket, $oldPriority->value));
        }
    }

    /**
     * Handle the Ticket "deleted" event.
     */
    public function deleted(Ticket $ticket): void
    {
        Storage::disk('private')->deleteDirectory('ticket-attachments/' . $ticket->id);
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
            $ticket->ticket_number = $this->generateTicketNumber($ticket);
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

        $ticket->is_opened = false;
        $ticket->opened_by = null;
        $ticket->opened_at = null;

    }

    protected function generateTicketNumber(Ticket $ticket): string
    {
        $date = now()->format('ymd');
        $random = strtoupper(Str::random(4));
        $format = '{PREFIX}-{DATE}-{RAND}';

        $ticket_number = str_replace(['{PREFIX}', '{DATE}', '{RAND}'], [$ticket->form->initial, $date, $random], $format);

        if (Ticket::where('ticket_number', $ticket_number)->exists()) {
            $this->generateTicketNumber($ticket);
        }

        return $ticket_number;
    }

    public function updating(Ticket $ticket): void
    {
        if ($ticket->isDirty('assigned_to')) {
            $oldAssigneeId = $ticket->getOriginal('assigned_to');
            $newAssigneeId = $ticket->assigned_to;

            $oldAssignee = $oldAssigneeId ? User::find($oldAssigneeId) : null;
            $newAssignee = $newAssigneeId ? User::find($newAssigneeId) : null;

            $ticket->activities()->create([
                'user_id' => auth()->id(),
                'description' => 'Ticket was assigned',
                'old_value' => $oldAssignee ? $oldAssignee->name : 'Unassigned',
                'new_value' => $newAssignee ? $newAssignee->name : 'Unassigned',
            ]);
        }

        if ($ticket->isDirty('ticket_status_id')) {
            $oldStatusId = $ticket->getOriginal('ticket_status_id');
            $newStatusId = $ticket->ticket_status_id;

            $oldStatus = TicketStatus::find($oldStatusId);
            $newStatus = TicketStatus::find($newStatusId);

            $ticket->activities()->create([
                'user_id' => auth()->id(),
                'description' => 'Status was changed',
                'old_value' => $oldStatus?->name,
                'new_value' => $newStatus?->name,
            ]);
        }

        if ($ticket->isDirty('priority')) {
            $oldPriority = $ticket->getOriginal('priority');
            $newPriority = $ticket->priority;

            if ($oldPriority instanceof TicketPriority) {
                $oldPriority = $oldPriority->getLabel();
            }
            if ($newPriority instanceof TicketPriority) {
                $newPriority = $newPriority->getLabel();
            }

            $ticket->activities()->create([
                'user_id' => auth()->id(),
                'description' => 'Priority was changed',
                'old_value' => $oldPriority,
                'new_value' => $newPriority,
            ]);
        }
    }

    public function deleting(Ticket $ticket): void
    {
        event(new TicketDeleted(
            $ticket->id,
            $ticket->ticket_number,
            auth()->user()
        ));
    }
}
