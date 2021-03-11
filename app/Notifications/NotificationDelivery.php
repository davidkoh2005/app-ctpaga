<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotificationDelivery extends Notification
{
    use Queueable;
    protected $message, $delivery;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message, $delivery)
    {
        $this->message = $message;
        $this->delivery = $delivery;
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
        $msg = 'Estás recibiendo este correo electrónico porque '.$this->message;

        return (new MailMessage)  
            ->subject('Delivery Ctpaga Aviso')
            ->markdown(
                'email.notificationDelivery', ['message' => $msg, 'delivery' => $this->delivery]
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
