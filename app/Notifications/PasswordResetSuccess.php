<?php
namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
class PasswordResetSuccess extends Notification implements ShouldQueue
{
    use Queueable;
    protected $emailFrom;
    /**
    * Create a new notification instance.
    *
    * @return void
    */
    public function __construct($emailFrom)
    {
        $this->emailFrom = $emailFrom;
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
            ->from($this->emailFrom) 
            ->line('Has cambiado tu contraseña correctamente.')
            ->line('Si cambió la contraseña, no se requiere ninguna otra acción.')
            ->line('Si no cambió la contraseña, proteja su cuenta.')
            ->subject('Restablecimiento de contraseña exitoso');
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