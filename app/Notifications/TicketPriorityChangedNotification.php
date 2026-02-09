<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketPriorityChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Ticket $ticket;
    public string $oldPriority;

    /**
     * Create a new notification instance.
     */
    public function __construct($ticket, $oldPriority)
    {
        $this->ticket = $ticket;
        $this->oldPriority = $oldPriority;
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
        return (new MailMessage)
            ->line('Ticket priority changed.')
            ->line('Previous priority: ' . $this->oldPriority)
            ->line('New priority: ' . $this->ticket->priority->name)
            ->line('Thank you for using our application!')
            ->action('Login', url('/'))
            ->salutation('Warm regards,')
            ->line(config('app.name'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
