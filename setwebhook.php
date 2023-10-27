<?php

require __DIR__ . '/vendor/autoload.php';
/** @var \Mateodioev\TgHandler\Bot $bot */
$bot = require __DIR__ . '/bot.php';

$api = $bot->getApi();
$url = readline('Enter the webhook url: ');

echo $api->setWebhook($url)
    ->toString(JSON_PRETTY_PRINT)
;
