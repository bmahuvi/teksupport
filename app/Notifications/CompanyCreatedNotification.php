<?php

namespace App\Notifications;

use App\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CompanyCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Company $company;
    public string $viewCompanyUrl;

    /**
     * Create a new notification instance.
     */
    public function __construct($company, $viewCompanyUrl)
    {
        $this->company = $company;
        $this->viewCompanyUrl = $viewCompanyUrl;
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
            ->subject('Company Created Successfully')
            ->greeting('Hello!')
            ->line('Your company has been created successfully.')
            ->line('Company Name: ' . $this->company->name)
            ->line('Email: ' . $this->company->email)
            ->line('Thank you for using our application!')
            ->action('Click to view company', url($this->viewCompanyUrl))
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
