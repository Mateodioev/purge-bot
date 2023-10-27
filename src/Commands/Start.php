<?php

namespace App\Commands;

use Mateodioev\TgHandler;
use Mateodioev\Bots\Telegram;

class Start extends TgHandler\Commands\MessageCommand
{
    protected string $name  = 'start';
    protected array $prefix = ['/', '!', '.'];

    public function handle(Telegram\Api $bot, TgHandler\Context $context, $args = [])
    {
        $this->api()->replyTo(
            $this->ctx()->getChatId(),
            self::getStartMessage($this->ctx()->getUser()),
            $this->ctx()->getMessageId()
        );
    }


    public static function getStartMessage(Telegram\Types\User $user): string
    {
        return sprintf(
            "Hi %s, I'm a bot to delete messages in bulk\n\nUsage:\n/purge reply to a message",
            $user->mention(customName: 'User')
        );
    }
}
