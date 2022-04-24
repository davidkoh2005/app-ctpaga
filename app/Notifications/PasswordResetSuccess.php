<?php

namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PasswordResetSuccess extends Notification 
{
    use Queueable;
    protected $type, $user;
    /**
    * Create a new notification instance.
    *
    * @return void
    */
    public function __construct($type, $user)
    {
        $this->type = $type;
        $this->user = $user;
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
        if($this->type == 0)
            $subject = "Aviso ".env('APP_NAME');
        else
            $subject = "Aviso Ctlleva";

        return (new MailMessage)
            ->subject($subject)
            ->markdown(
                'email.passwordSuccess', ['user' => $this->user]
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