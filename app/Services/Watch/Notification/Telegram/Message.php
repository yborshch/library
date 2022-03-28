<?php

namespace App\Services\Watch\Notification\Telegram;

use Illuminate\Notifications\Notifiable;

class Message
{
    use Notifiable;

    /**
     * @var string
     */
    public string $author;

    /**
     * @var string
     */
    public string $series;

    /**
     * @var string
     */
    public string $bookImage;

    /**
     * @var string
     */
    public string $bookTitle;

    /**
     * @var string
     */
    public string $bookLink;

    /**
     * @return int
     */
    public function routeNotificationForTelegram(): int
    {
        return env('TELEGRAM_CHAT_ID');
    }
}
