<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentCancel extends Notification
{
    use Queueable;
    protected $nameClient, $codeUrl;
    /**
    * Create a new notification instance.
    *
    * @return void
    */
    public function __construct($nameClient, $codeUrl)
    {
        $this->nameClient = $nameClient;
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
            ->subject('Pago Rechazado')
            ->markdown(
            'email.paymentCancel', ['nameClient' => $this->nameClient, 'codeUrl' => $this->codeUrl]
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
