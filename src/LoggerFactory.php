<?php

namespace App;

use Amp\ByteStream;
use Amp\Log\{ConsoleFormatter, StreamHandler};
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;

class LoggerFactory
{
    public static function create(string $name): Logger
    {
        $logger = new Logger($name);
        $logger->pushHandler(self::getStdOutHandler());

        return $logger;
    }

    private static function getStdOutHandler(): StreamHandler
    {
        $logHandler = new StreamHandler(ByteStream\getStdout());
        $logHandler->pushProcessor(new PsrLogMessageProcessor());
        $logHandler->setFormatter(new ConsoleFormatter());

        return $logHandler;
    }
}
