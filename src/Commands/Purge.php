<?php

namespace App\Commands;

use App\FilterPublicChat;
use Mateodioev\Bots\Telegram\Api;
use Mateodioev\TgHandler\Context;
use Mateodioev\TgHandler\Commands\MessageCommand;
use Mateodioev\Bots\Telegram\Exception\TelegramApiException;

use function Amp\async;
use function Amp\Future\awaitAll;

#[FilterPublicChat]
class Purge extends MessageCommand
{
    private const SPLIT_SIZE = 15;

    protected string $name  = 'purge';
    protected array $prefix = ['/', '!', '.'];

    public function handle(Api $bot, Context $context, $args = [])
    {
        $startTime = microtime(true);

        $chatID = $context->getChatId();
        $id     = $context->message()?->replyToMessage()?->messageId();

        if ($id === null) {
            $this->isNotReply();
            return;
        }

        $ids = range($id, $this->ctx()->message()->messageId() - 1);

        $this->logger()->info('Deleting {count} messages in chat {chatId}', [
            'count'  => count($ids),
            'chatId' => $chatID
        ]);
        $this->logger()->debug('Message id\'s: {ids}', ['ids' => \json_encode(array_values($ids))]);

        $ids = array_chunk($ids, self::SPLIT_SIZE); // Borra de 10 en 10

        $asyncFns = [];

        foreach ($ids as $collectionId) {
            foreach ($collectionId as $id) {
                $asyncFns[] = $this->createAsyncDelete($chatID, $id);
            }

            awaitAll($asyncFns);
            $asyncFns = [];
            $this->sleep(1);
        }

        $this->finishedDelete(microtime(true) - $startTime);
    }

    private function isNotReply(): void
    {
        $this->api()->replyToMessage($this->ctx()->message, '<b>Please reply to a message to delete</b>');
    }

    private function finishedDelete(float $totalTime): void
    {
        $this->api()->replyToMessage($this->ctx()->message, 'Purge finished in <i>' . round($totalTime, 2) . '\'s</i>');
    }

    private function createAsyncDelete(int $chatID, int $messageID): \Amp\Future
    {
        return async(
            function () use ($chatID, $messageID): void {
                try {
                    $this->api()->deleteMessage($chatID, $messageID);
                } catch (TelegramApiException $e) {
                    $this->logger()->debug('Fail to delete message id {id} in chat id: {chatID}', [
                        'id'     => $messageID,
                        'chatID' => $chatID
                    ]);
                    $this->logger->debug('Error: {e}', ['e' => $e->getMessage()]);
                }

            }
        );
    }
}
