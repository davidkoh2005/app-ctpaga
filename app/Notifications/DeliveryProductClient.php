<?php

namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DeliveryProductClient extends Notification 
{
    use Queueable;
    protected $commerce, $paid, $sales;
    /**
    * Create a new notification instance.
    *
    * @return void
    */
    public function __construct($commerce, $paid, $sales)
    {
        $this->commerce = $commerce;
        $this->paid = $paid;
        $this->sales = $sales;
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
            ->subject("Aviso Delivery Ctpaga")
            ->markdown(
                'email.deliveryProductClient', ['commerce' => $this->commerce, 'paid' => $this->paid, 'sales' => $this->sales]
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