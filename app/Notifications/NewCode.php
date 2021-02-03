<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCode extends Notification
{
    use Queueable;

    protected $codeUrl;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($codeUrl)
    {
        $this->codeUrl = $codeUrl;
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
            ->line('Estás recibiendo este correo electrónico porque recibiste nuevo código de compra.')
            ->line('Código:'+$this->codeUrl)
            ->line('Si no recibiste notificación, copia y pega en la aplicación Delivery Ctpaga.');
            ->subject('Aviso Delivery Ctpaga');
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
