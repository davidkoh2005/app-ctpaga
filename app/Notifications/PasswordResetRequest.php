<?php
namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PasswordResetRequest extends Notification implements ShouldQueue
{
    use Queueable;
    protected $token, $type, $user;
    /**
    * Create a new notification instance.
    *
    * @return void
    */
    public function __construct($token, $type, $user)
    {
        $this->token = $token;
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
        if($this->type == 0){
            $url = url('/password/find/'.$this->token);
            $subject = "Aviso CTpaga";
        }
        else{
            $url = url('/password/delivery/find/'.$this->token);
            $subject = "Aviso Delivery CTpaga";
        }

        return (new MailMessage)
            ->subject($subject)
            ->markdown(
                'email.passwordReset', ['user' => $this->user, 'url' => $url]
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