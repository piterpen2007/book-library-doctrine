<?php
namespace EfTech\BookLibrary\Infrastructure\Logger\NullLogger;
require_once __DIR__ . '/../LoggerInterface.php';

use EfTech\BookLibrary\Infrastructure\Logger\LoggerInterface;

/**
 *  Логгирует в никуда
 */
class Logger implements LoggerInterface
{
    /**
     * @inheritDoc
     *
     */
    public function log(string $msg): void
    {
        // TODO: Implement log() method.
    }

}