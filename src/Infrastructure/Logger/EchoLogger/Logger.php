<?php
namespace Infrastructure\Logger\EchoLogger;

require_once __DIR__ . '/../LoggerInterface.php';

/**
 *  Логирует в консоль с помощью echo
 */
class Logger implements \Infrastructure\Logger\LoggerInterface
{

    /**
     * @inheritDoc
     */
    public function log(string $msg): void
    {
        echo "$msg\n";
    }
}