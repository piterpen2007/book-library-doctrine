<?php

namespace EfTech\BookLibraryTest\DI;

use EfTech\BookLibrary\Config\AppConfig;
use EfTech\BookLibrary\Controller\GetAuthorsCollectionController;
use EfTech\BookLibrary\Entity\AuthorRepositoryInterface;
use EfTech\BookLibrary\Infrastructure\DI\Container;
use EfTech\BookLibrary\Infrastructure\Logger\Adapter\NullAdapter;
use EfTech\BookLibrary\Infrastructure\Logger\AdapterInterface;
use EfTech\BookLibrary\Infrastructure\Logger\Logger;
use EfTech\BookLibrary\Repository\AuthorJsonFileRepository;
use EfTech\BookLibrary\Service\SearchAuthorsService;
use PHPUnit\Framework\TestCase;

/**
 * Тестирование получения сервиса
 */
class ContainerTest extends TestCase
{
    /**
     * Тестирование получения сервиса
     */
    public function testGetService(): void
    {
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
                AuthorRepositoryInterface::class => [
                    'class' => AuthorJsonFileRepository::class,
                    'args' => [
                        'pathToAuthors' => 'pathToAuthors',
                        'dataLoader' => \EfTech\BookLibrary\Infrastructure\DataLoader\DataLoaderInterface::class
                    ]

                ],
                SearchAuthorsService::class => [
                    'args' => [
                        'logger' => \EfTech\BookLibrary\Infrastructure\Logger\LoggerInterface::class,
                        'authorRepository' => AuthorRepositoryInterface::class
                    ]
                ],
                \EfTech\BookLibrary\Infrastructure\Logger\LoggerInterface::class => [
                    'class' => Logger::class,
                    'args' => [
                        'adapter' => AdapterInterface::class
                    ]
                ],
                AdapterInterface::class => [
                    'class' => NullAdapter::class
                ]
            ],
            'factories' => [
                'pathToLogFile' => static function (Container $c) {
                    /** @var \EfTech\BookLibrary\Config\AppConfig $appConfig */
                    $appConfig = $c->get(AppConfig::class);
                    return $appConfig->getPathToLogFile();
                },
                \EfTech\BookLibrary\Config\AppConfig::class => static function (Container $c) {
                    $appConfig = $c->get('appConfig');
                    return AppConfig::createFromArray($appConfig);
                },
                'pathToAuthors' => static function (Container $c) {
                    /** @var \EfTech\BookLibrary\Config\AppConfig $appConfig */
                    $appConfig = $c->get(AppConfig::class);
                    return $appConfig->getPathToAuthor();
                },
            ],
        ];
        $di = Container::createFromArray($diConfig);

        //Act
        $controller = $di->get(GetAuthorsCollectionController::class);

        //Assert
        $this->assertInstanceOf(
            GetAuthorsCollectionController::class,
            $controller,
            'Ошибка создания контейнера'
        );
    }
}
