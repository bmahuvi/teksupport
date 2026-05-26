<?php

namespace App\Notifications;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApproveTicketNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Ticket $ticket;
    public User $user;
    public string $viewTicketUrl;

    /**
     * Create a new notification instance.
     */
    public function __construct(Ticket $ticket, User $user, string $viewTicketUrl)
    {
        $this->ticket = $ticket;
        $this->user = $user;
        $this->viewTicketUrl = $viewTicketUrl;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $appName = config('app.name');
        return (new MailMessage)
            ->subject('Approve Ticket')
            ->line("We inform you that a new ticket has been created in the platform {$appName}.")
            ->line('It requires your approval.')
            ->line('Below are the details of this ticket:')
            ->line("- Title: {$this->ticket->title}")
            ->line("- Category: {$this->ticket->form?->name}")
            ->line("- Priority: {$this->ticket->priority->getLabel()}")
            ->line("- Created By: {$this->ticket->createdBy->name}")
            ->line("- From: {$this->ticket->company->name}")
            ->action('Ticket Details', url($this->viewTicketUrl))
            ->salutation('Warm regards,');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [];
    }
}
