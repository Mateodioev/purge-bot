<?php

use Dotenv\Dotenv;
use Mateodioev\TgHandler\Bot;
use App\Commands\{Purge, Start, All};
use Mateodioev\TgHandler\Log\{BulkStream, FileStream, Logger, TerminalStream};

require __DIR__ . '/vendor/autoload.php';

Dotenv::createImmutable(__DIR__)->load();

$bot = new Bot(env('BOT_TOKEN'));
$bot->setLogger(
    new Logger(
        new BulkStream(new TerminalStream(), new FileStream(__DIR__ . '/info.log')),
    )
);

$bot->onEvent(Purge::get());
$bot->onEvent(Start::get());
$bot->onEvent(new All());

$bot->longPolling(30, true, true);
