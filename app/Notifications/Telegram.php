<?php

namespace App\Notifications;

use App\Services\Watch\Notification\Telegram\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramFile;
use NotificationChannels\Telegram\TelegramMessage;

class Telegram extends Notification
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
    public function via(mixed $notifiable): array
    {
        return ['telegram'];
    }

    public function toTelegram($notifiable): TelegramFile
    {
        if ($notifiable instanceof Message) {
            $message = "*Вышла новая книга*\r\nАвтор: $notifiable->author\r\n";
            if (!empty($notifiable->series)) {
                $message = $message . "Серия: $notifiable->series\r\n";
            }
            $message = $message . "Название: $notifiable->bookTitle\r\n";
            return TelegramFile::create()
                ->content("$message")
                ->file($notifiable->bookImage, 'photo')
                ->button("Подробнее: ", $notifiable->bookLink);
        }
        return new TelegramFile();
    }
}
