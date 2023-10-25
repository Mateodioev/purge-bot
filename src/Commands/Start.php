<?php

namespace App\Commands;

use Mateodioev\TgHandler\Commands\MessageCommand;
use Mateodioev\Bots\Telegram\Api;
use Mateodioev\Bots\Telegram\Types\User;
use Mateodioev\TgHandler\Context;

class Start extends MessageCommand
{
    protected string $name  = 'start';
    protected array $prefix = ['/', '!', '.'];

    public function handle(Api $bot, Context $context, $args = [])
    {
        $this->api()->replyTo(
            $this->ctx()->getChatId(),
            self::getStartMessage($this->ctx()->getUser),
            $this->ctx()->getMessageId()
        );
    }


    public function getStartMessage(User $user): string
    {
        return sprintf(
            "Hi %s, I'm a bot to delete messages in bulk\n\nUsage:\n/purge reply to a message",
            $user->mention(customName: 'User')
        );
    }
}
