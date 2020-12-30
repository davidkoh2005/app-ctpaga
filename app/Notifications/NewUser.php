<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUser extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        ->subject('Bienvenido a Ctpaga')
        ->line('Bienvenido a Ctpaga. Inicia el negocio de tus sueños en segundos ¡Manéjalo desde tu celular y sin complicaciones!. Rellena los datos de la empresa y bancaria')
        ->line('Al subir la fotografía de Selfie y Documentos')
        ->line('* Asegurate que al tomar la fotografía, el ambiente se encuentre bien iluminado y no haya resplandores. La luz del día es la más conveniente.')
        ->line('* Asegirate que al tomar selfie no utilices anteojos ni sombreros.')
        ->line('* Asegurate que tu documento tenga como mínimo 3 meses de validez y que no se encuentre agujereado ni dañado.')
        ->line('* Asegurate que el documento sea visible en su totalidad y que la fotografía esté enfocada.');
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
