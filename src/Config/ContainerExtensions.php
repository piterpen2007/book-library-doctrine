<?php

namespace EfTech\BookLibrary\Config;

use EfTech\BookLibrary\Infrastructure\http\SymfonyDi\DiHttpExt;
use EfTech\BookLibrary\Infrastructure\Logger\SymfonyDi\DiLoggerExt;
use EfTech\BookLibrary\Infrastructure\Router\SymfonyDi\DiRouterExt;

final class ContainerExtensions
{
    /** Возвращает коллекцию расширений di контейнера симфони для работу http риложения
     * @return mixed
     */
    public static function httpAppContainerExtension(): array
    {
        return [
            new DiRouterExt(),
            //new DiLoggerExt(),
            new DiHttpExt()
        ];
    }

    /** Возвращает коллекцию расширений di контейнера симфони для работы консольного приложения
     * @return array
     */
    public static function consoleContainerExtension(): array
    {
        return [
            new DiRouterExt(),
            //new DiLoggerExt(),
            new DiHttpExt()
        ];
    }
}
