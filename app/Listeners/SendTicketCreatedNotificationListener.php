<?php

namespace App\Listeners;

use App\Events\TicketCreated;
use App\Models\User;
use App\Notifications\ApproveTicketNotification;
use App\Notifications\TicketCreatedNotification;

class SendTicketCreatedNotificationListener
{
    public function __construct()
    {
    }

    public function handle(TicketCreated $event): void
    {
        $ticket = $event->ticket;

        $ticket->load([
            'form',
            'createdBy',
            'company',
        ]);

        $user = $event->user;
        $viewTicketUrl = $event->viewTicketUrl;

        $recipients = User::query()
            ->whereHas('company', function ($query) {
                $query->where('is_main', true);
            })
            ->where('id', '!=', $user?->id)
            ->get();

        if ($ticket->form->requires_approval) {
            $approvers = User::query()
                ->where('company_id', $ticket->company_id)
                ->where('id', '!=', $user?->id)
                ->get()
                ->filter(fn(User $recipient) => $recipient->can('Approve:Ticket'));

            foreach ($approvers as $recipient) {
                $recipient->notify(
                    new ApproveTicketNotification($ticket, $user, $viewTicketUrl)
                );
            }
        } else {
            foreach ($recipients as $recipient) {
                $recipient->notify(
                    new TicketCreatedNotification($ticket, $user, $viewTicketUrl)
                );
            }
        }
    }
}
