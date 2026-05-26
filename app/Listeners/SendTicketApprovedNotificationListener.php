<?php

namespace App\Listeners;

use App\Events\TicketApproved;
use App\Models\User;
use App\Notifications\TicketCreatedNotification;

class SendTicketApprovedNotificationListener
{
    public function __construct()
    {
    }

    public function handle(TicketApproved $event): void
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

        if ($ticket->status === 'Approved') {
            foreach ($recipients as $recipient) {
                $recipient->notify(
                    new TicketCreatedNotification($ticket, $user, $viewTicketUrl)
                );
            }
        }
    }
}
