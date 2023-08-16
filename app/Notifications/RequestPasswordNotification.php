<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class RequestPasswordNotification extends Notification
{
    use Queueable;

    public function __construct(public string $token){}

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(User $notifiable): MailMessage
    {
		$url = URL::temporarySignedRoute('request_password.reset_form', now()->addMinutes(value: 15), [
            'email' => $notifiable->email,
            'token' => $this->token,
        ]);

        return (new MailMessage)
			->subject(config(key: 'app.name') . ' Passwort angeben')
			->line('Sie haben für WeNoM - WebNotenManager ein neues Passwort angefordert. Klicken Sie auf diesen link, um das Passwort zu ändern:')
			->action('Neues Passwort eingeben', url: $url)
			->line('Dieser Link ist 15 Minuten gültig. Während dieser Zeit kann kein neues Passwort angefordert werden.')
			->line('Falls Sie nicht das neue Passwort angefordert haben, können Sie diese mail ignorieren.');
    }
}
