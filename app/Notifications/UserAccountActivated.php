<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Twilio\Rest\Client;

class UserAccountActivated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
    */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
    */
    public function via(object $notifiable): array
    {
        return ['database', 'mail', 'twilio'];
    }

    /**
     * Get the mail representation of the notification.
    */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
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

    // Method for Twilio
    public function toTwilio($notifiable)
    {
        $message = "Hi {$notifiable->name}, your account on LaraDash has been activated! You can now log in.";
        
        $client = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
        
        return $client->messages->create(
            $notifiable->phone_number, // User model needs a 'phone_number' column
            [
                'from' => env('TWILIO_PHONE_NUMBER'),
                'body' => $message
            ]
        );
    }
}