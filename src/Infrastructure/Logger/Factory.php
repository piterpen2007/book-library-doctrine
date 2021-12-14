<?php
namespace EfTech\BookLibrary\Infrastructure\Logger;
use Exception;
use EfTech\BookLibrary\Infrastructure\AppConfig;
use EfTech\BookLibrary\Infrastructure\Logger\FileLogger as FileLogger;
use EfTech\BookLibrary\Infrastructure\Logger\NullLogger as NullLogger;
use EfTech\BookLibrary\Infrastructure\Logger\EchoLogger as EchoLogger;

/**
 *  Фабрика по созданию логеров
 */
class Factory
{
    /** Реализация логики создания логеров
     *
     * @param AppConfig $appConfig
     * @throws Exception
     * @return LoggerInterface
     */
    public static function create(AppConfig $appConfig): LoggerInterface
    {
        if ('fileLogger' === $appConfig->getLoggerType()) {
            $logger = new FileLogger\Logger($appConfig->getPathToLogFile());
        } elseif ('nullLogger' === $appConfig->getLoggerType()) {
            $logger = new NullLogger\Logger();
        } elseif ('echoLogger' === $appConfig->getLoggerType()) {
            $logger = new EchoLogger\Logger();
        } else {
            throw new Exception('Unknown logger type');
        }
        return $logger;

    }

    public function __construct()
    {
    }

}