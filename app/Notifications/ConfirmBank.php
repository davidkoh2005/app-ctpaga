<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConfirmBank extends Notification
{
    use Queueable;
    protected $user, $commerce;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $commerce)
    {
        $this->user = $user;
        $this->commerce = $commerce;
    }
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
        ->subject('Bienvenido a '.env('APP_NAME'))
        ->markdown(
            'email.confirmBank', ['user' => $this->user, 'commerce' => $this->commerce]
        );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
