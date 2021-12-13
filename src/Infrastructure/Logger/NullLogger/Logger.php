<?php
namespace Infrastructure\Logger\NullLogger;
require_once __DIR__ . '/../LoggerInterface.php';

/**
 *  Логгирует в никуда
 */
class Logger implements \Infrastructure\Logger\LoggerInterface
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