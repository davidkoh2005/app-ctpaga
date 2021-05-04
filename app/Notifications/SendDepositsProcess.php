<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendDepositsProcess extends Notification
{
    use Queueable;
    protected $user, $deposits;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $deposits)
    {
        $this->user = $user;
        $this->deposits = $deposits;
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
            ->subject('Aviso CTpaga')
            ->markdown(
                'email.sendDepositsProcess', ['user' => $this->user, 'deposits' => $this->deposits,]
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
