<?php

use Dotenv\Dotenv;
use Mateodioev\TgHandler\Bot;
use App\Commands\{Purge, Start, All};
use App\MemoryDbAdapter;
use Mateodioev\TgHandler\Log\{BulkStream, FileStream, Logger, TerminalStream};

Dotenv::createImmutable(__DIR__)->load();

$bot = new Bot(env('BOT_TOKEN'));
$bot->setDb(new MemoryDbAdapter());

$logger = new Logger(
    new BulkStream(new TerminalStream(), new FileStream(__DIR__ . '/info.log')),
);
$logger->setLevel(Logger::DEBUG, false); // Disable debug logs

$bot->setLogger($logger)
    ->onEvent(Purge::get())
    ->onEvent(Start::get())
    ->onEvent(new All())
;

return $bot;
