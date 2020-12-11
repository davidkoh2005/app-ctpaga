<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostPurchase extends Notification
{
    use Queueable;
    protected $message, $userUrl, $name;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message, $userUrl, $name)
    {
        $this->message = $message;
        $this->userUrl = $userUrl;
        $this->name = $name;
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
        $url = url($this->userUrl);
        return (new MailMessage)  
            ->subject('Gracias por realizar tu compra')
            ->markdown(
            'email.postPurchase', ['url' => $url, 'message' => $this->message, "name" => $this->name]
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
