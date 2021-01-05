<?php
namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
class PasswordResetRequest extends Notification implements ShouldQueue
{
    use Queueable;
    protected $token, $type;
    /**
    * Create a new notification instance.
    *
    * @return void
    */
    public function __construct($token, $type)
    {
        $this->token = $token;
        $this->type = $type;
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
            $url = url('/password/find/'.$this->token);
        else
            $url = url('/password/delivery/find/'.$this->token);

        return (new MailMessage)
            ->line('Estás recibiendo este correo electrónico porque recibimos una solicitud de restablecimiento de contraseña para tu cuenta.')
            ->action('Cambiar contraseña', url($url))
            ->line('Si no solicitó un restablecimiento de contraseña, no es necesario realizar ninguna otra acción.')
            ->subject('Aviso Ctpaga');
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