<?php

namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DeliveryProductClientInitial extends Notification 
{
    use Queueable;
    protected $commerce, $paid, $sales, $delivery;
    /**
    * Create a new notification instance.
    *
    * @return void
    */
    public function __construct($commerce, $paid, $sales, $delivery)
    {
        $this->commerce = $commerce;
        $this->paid = $paid;
        $this->sales = $sales;
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
        $url = url('/delivery/'.$this->delivery->idUrl);
        return (new MailMessage)
            ->subject("Aviso Delivery ".env('APP_NAME'))
            ->markdown(
                'email.deliveryProductClientInitial', ['commerce' => $this->commerce, 'paid' => $this->paid, 'sales' => $this->sales, 'url' => $url]
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