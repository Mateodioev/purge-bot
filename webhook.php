<?php

use Amp\Http\HttpStatus;
use Amp\Http\Server\{
    DefaultErrorHandler,
    Request,
    Response,
    SocketHttpServer
};
use App\{LoggerFactory, RequestHandler};

require __DIR__ . '/vendor/autoload.php';
/** @var \Mateodioev\TgHandler\Bot $bot */
$bot = require __DIR__ . '/bot.php';

$logger = LoggerFactory::create('webhook');

$requestHandler = RequestHandler::new(function (Request $req) use ($bot, $logger): Response {
    $body = $req->getBody()->read();

    if (empty($body)) {
        return new Response(
            HttpStatus::OK,
            ['content-type' => 'text/plain'],
            'Empty body'
        );
    }

    $logger->info('Webhook request received {body}', ['body' => $body]);
    $body = json_decode($body, true, JSON_THROW_ON_ERROR);
    $logger->info('Decoded body {body}', ['body' => $body]);
    if ($body == null) {
        return new Response(
            HttpStatus::OK,
            ['content-type' => 'text/plain'],
            'Invalid body'
        );
    }
    $bot->byWebhook($body, async: true);

    return new Response(
        HttpStatus::OK,
        ['content-type' => 'text/plain'],
        'Hello, World!'
    );
});

$errorHandler = new DefaultErrorHandler();

$server = SocketHttpServer::createForDirectAccess($logger);
$server->expose('127.0.0.1:1337');
$server->start($requestHandler, $errorHandler);

// Serve requests until SIGINT or SIGTERM is received by the process.
Amp\trapSignal([SIGINT, SIGTERM]);

$server->stop();
