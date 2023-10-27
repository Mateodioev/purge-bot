<?php

use Mateodioev\TgHandler\Db\Memory;

require __DIR__ . '/vendor/autoload.php';
/** @var \Mateodioev\TgHandler\Bot $bot */
$bot = require __DIR__ . '/bot.php';

$bot->setDb(new Memory()); // Use default memory database on long polling

$bot->getApi()->deleteWebhook(dropUpdates: true); // Drop webhook updates

$bot->longPolling(
    timeout: 60,
    ignoreOldUpdates: true,
    async: true,
);
