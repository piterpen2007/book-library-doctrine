<?php

namespace EfTech\BookLibraryTest\Infrastructure\DI;

use EfTech\BookLibrary\Controller\FindAuthors;
use EfTech\BookLibrary\Infrastructure\AppConfig;
use EfTech\BookLibrary\Infrastructure\Autoloader;
use EfTech\BookLibrary\Infrastructure\DI\Container;
use EfTech\BookLibrary\Infrastructure\DI\ContainerInterface;
use EfTech\BookLibrary\Infrastructure\Logger\FileLogger\Logger;
use EfTech\BookLibrary\Infrastructure\Logger\LoggerInterface;


require_once __DIR__ . '/../../src/Infrastructure/Autoloader.php';

spl_autoload_register(
    new Autoloader([
        'EfTech\\BookLibrary\\' => __DIR__ . '/../../src/',
        'EfTech\\BookLibraryTest\\' => __DIR__ . '/../../test/'
    ])
);
class ContainerTest
{
    /**
     * Тестирование получения сервиса
     */
    public static function testGetService():void
    {
        echo "------------------Тестирование получения сервиса---------------\n";
        //Arrange
        $diConfig = [
            'instances'=> [
                'appConfig' =>require __DIR__ . '/../../config/dev/config.php'
            ],
            'services' => [
                FindAuthors::class => [
                    'args' => [
                        'appConfig' => AppConfig::class,
                        'logger' => LoggerInterface::class
                    ]
                ],
                LoggerInterface::class => [
                    'class' => Logger::class,
                    'args' => [
                        'pathToFile' => 'pathToLogFile'
                    ]
                ]

            ],
            'factories' => [
                'pathToLogFile' => static function(ContainerInterface $c):string {
                    /** @var AppConfig $appConfig */
                   $appConfig = $c->get(AppConfig::class);
                   return $appConfig->getPathToLogFile();
                },
                AppConfig::class => static function(ContainerInterface $c): AppConfig {
                    $appConfig = $c->get('appConfig');
                    return AppConfig::createFromArray($appConfig);
                }

            ]
        ];
        $di = Container::createFromArray($diConfig);
        //Act
        $controller = $di->get(FindAuthors::class);

        //Assert
        if ($controller instanceof FindAuthors) {
            echo "     ОК - di контейнер отработал корректно";
        } else {
            echo "     FAIL - di контейнер отработал корректно";
        }
    }

}

ContainerTest::testGetService();