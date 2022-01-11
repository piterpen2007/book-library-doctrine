<?php
namespace EfTech\BookLibrary\Infrastructure\Logger\EchoLogger;
use EfTech\BookLibrary\Infrastructure\Logger\LoggerInterface;

/**
 *  Логирует в консоль с помощью echo
 */
class Logger implements LoggerInterface
{

    /**
     * @inheritDoc
     */
    public function log(string $msg): void
    {
        echo "$msg\n";
    }
}