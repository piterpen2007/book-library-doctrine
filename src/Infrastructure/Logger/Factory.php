<?php
require_once __DIR__ . '/LoggerInterface.php';

/**
 *  Фабрика по созданию логеров
 */
class Factory
{
    /** Реализация логики создания логеров
     * @param AppConfig $appConfig
     * @throws Exception
     */
    public static function create(AppConfig $appConfig): LoggerInterface
    {
        if ('fileLogger' === $appConfig->getLoggerType()) {
            require_once __DIR__ . '/FileLogger/Logger.php';
            $logger = new Logger($appConfig->getPathToLogFile());
        } elseif ('nullLogger' === $appConfig->getLoggerType()) {
            require_once __DIR__ . '/NullLogger/Logger.php';
            $logger = new Logger();
        } elseif ('echoLogger' === $appConfig->getLoggerType()) {
            require_once __DIR__ . '/EchoLogger/Logger.php';
            $logger = new Logger();
        } else {
            throw new Exception('Unknown logger type');
        }
        return $logger;

    }

    public function __construct()
    {
    }

}