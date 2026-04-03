<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketCreatedNotification extends Notification
{
    use Queueable;

    protected $ticket;

    public function __construct(Ticket $ticket) { $this->ticket = $ticket; }

    public function via($notifiable): array { return ['mail']; }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Заявка №' . $this->ticket->id . ' принята')
            ->greeting('Здравствуйте, ' . $this->ticket->customer->name . '!')
            ->line('Ваше обращение по теме "' . $this->ticket->subject . '" успешно зарегистрировано.')
            ->line('Мы свяжемся с вами в ближайшее время.')
            ->action('Перейти на сайт', url('/'))
            ->line('Спасибо, что выбрали наш сервис!');
    }
}