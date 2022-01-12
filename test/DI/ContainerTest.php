<?php

namespace EfTech\BookLibraryTest\Infrastructure\DI;

use EfTech\BookLibrary\Controller\GetAuthorsCollectionController;
use EfTech\BookLibrary\Infrastructure\AppConfig;
use EfTech\BookLibrary\Infrastructure\Autoloader;
use EfTech\BookLibrary\Infrastructure\DI\Container;
use EfTech\BookLibrary\Service\SearchAuthorsService;


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
        //Arrange
        $diConfig = [
            'instances' => [
                'appConfig' => require __DIR__ . '/../../config/dev/config.php',
            ],
            'services' => [
                \EfTech\BookLibrary\Infrastructure\DataLoader\DataLoaderInterface::class => [
                    'class' => \EfTech\BookLibrary\Infrastructure\DataLoader\JsonDataLoader::class
                ],
                \EfTech\BookLibrary\Controller\GetAuthorsCollectionController::class => [
                    'args' => [
                        'logger' => \EfTech\BookLibrary\Infrastructure\Logger\LoggerInterface::class,
                        'searchAuthorsService' => SearchAuthorsService::class,
                        ]
                ],
                SearchAuthorsService::class => [
                    'args' => [
                        'logger' => \EfTech\BookLibrary\Infrastructure\Logger\LoggerInterface::class,
                        'pathToAuthors' => 'pathToAuthors',
                        'dataLoader' => \EfTech\BookLibrary\Infrastructure\DataLoader\DataLoaderInterface::class
                    ]
                ],
                \EfTech\BookLibrary\Infrastructure\Logger\LoggerInterface::class => [
                    'class' => \EfTech\BookLibrary\Infrastructure\Logger\FileLogger\Logger::class,
                    'args' => [
                        'pathToFile' => 'pathToLogFile'
                    ]
                ],
            ],
            'factories' => [
                'pathToLogFile' => static function(Container $c) {
                    /** @var AppConfig $appConfig */
                    $appConfig = $c->get(AppConfig::class);
                    return $appConfig->getPathToLogFile();
                    },
                \EfTech\BookLibrary\Infrastructure\AppConfig::class => static function(Container $c) {
            $appConfig = $c->get('appConfig');
            return AppConfig::createFromArray($appConfig);
            },
                'pathToAuthors' => static function(Container $c) {
            /** @var AppConfig $appConfig */
                    $appConfig = $c->get(AppConfig::class);
                    return $appConfig->getPathToAuthor();
                    },
                ],
            ];


        $di = Container::createFromArray($diConfig);
        //Act
        $controller = $di->get(GetAuthorsCollectionController::class);

        //Assert
        if ($controller instanceof GetAuthorsCollectionController) {
            echo "     ОК - di контейнер отработал корректно";
        } else {
            echo "     FAIL - di контейнер отработал корректно";
        }
    }

}

ContainerTest::testGetService();