<?php

use Dotenv\Dotenv;
use Mateodioev\TgHandler\Bot;
use App\Commands\{Purge, Start, All};
use App\MemoryDbAdapter;
use Mateodioev\TgHandler\Log\{BulkStream, FileStream, Logger, TerminalStream};

if ($argv[0] === \basename(__FILE__)) {
    fprintf(STDERR, "Do not run this file directly\n");
    exit(1);
}

Dotenv::createImmutable(__DIR__)->load();

$logger = new Logger(
    new BulkStream( // Log to both terminal and file
        new TerminalStream(),
        new FileStream(env('PWD_PATH', __DIR__) . '/info.log')
    ),
);
// $logger->setLevel(Logger::DEBUG, false); // Disable debug logs

$bot = new Bot(env('BOT_TOKEN'), $logger);
$bot->setDb(new MemoryDbAdapter());

$bot->setLogger($logger)
    ->onEvent(Purge::get())
    ->onEvent(Start::get())
    ->onEvent(new All());

return $bot;
