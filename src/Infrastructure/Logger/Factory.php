<?php
namespace EfTech\BookLibrary\Infrastructure\Logger;
use Exception;
use EfTech\BookLibrary\Infrastructure\AppConfig;
require_once __DIR__ . '/LoggerInterface.php';
require_once __DIR__ . '/../AppConfig.php';

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
            require_once __DIR__ . '/FileLogger/Logger.php';
            $logger = new \EfTech\BookLibrary\Infrastructure\Logger\FileLogger\Logger($appConfig->getPathToLogFile());
        } elseif ('nullLogger' === $appConfig->getLoggerType()) {
            require_once __DIR__ . '/NullLogger/Logger.php';
            $logger = new \EfTech\BookLibrary\Infrastructure\Logger\NullLogger\Logger();
        } elseif ('echoLogger' === $appConfig->getLoggerType()) {
            require_once __DIR__ . '/EchoLogger/Logger.php';
            $logger = new \EfTech\BookLibrary\Infrastructure\Logger\EchoLogger\Logger();
        } else {
            throw new Exception('Unknown logger type');
        }
        return $logger;

    }

    public function __construct()
    {
    }

}