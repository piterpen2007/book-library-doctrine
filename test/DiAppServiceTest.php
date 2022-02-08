<?php

namespace EfTech\BookLibraryTest;

use EfTech\BookLibrary\Config\AppConfig;
use EfTech\BookLibrary\Config\ContainerExtensions;
use EfTech\BookLibrary\ConsoleCommand\FindAuthors;
use EfTech\BookLibrary\ConsoleCommand\FindBooks;
use EfTech\BookLibrary\ConsoleCommand\HashStr;
use EfTech\BookLibrary\Controller\CreateRegisterBooksController;
use EfTech\BookLibrary\Controller\CreateRegisterMagazinesController;
use EfTech\BookLibrary\Controller\GetAuthorsCollectionController;
use EfTech\BookLibrary\Controller\GetAuthorsController;
use EfTech\BookLibrary\Controller\GetBooksCollectionController;
use EfTech\BookLibrary\Controller\GetBooksController;
use EfTech\BookLibrary\Controller\LoginController;
use EfTech\BookLibrary\Controller\TextDocumentAdministrationController;
use EfTech\BookLibrary\Controller\UpdateMoveToArchiveBooksController;
use EfTech\BookLibrary\Infrastructure\DI\SymfonyDiContainerInit;
use EfTech\BookLibrary\Infrastructure\Logger\Logger;
use EfTech\BookLibrary\Infrastructure\Logger\LoggerInterface;
use EfTech\BookLibrary\Infrastructure\Router\ChainRouters;
use EfTech\BookLibrary\Infrastructure\Router\DefaultRouter;
use EfTech\BookLibrary\Infrastructure\Router\RegExpRouter;
use EfTech\BookLibrary\Infrastructure\Router\RouterInterface;
use EfTech\BookLibrary\Infrastructure\Router\UniversalRouter;
use EfTech\BookLibrary\Infrastructure\View\DefaultRender;
use EfTech\BookLibrary\Infrastructure\View\RenderInterface;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 *  Тестирование создания сервисов приложения
 */
class DiAppServiceTest extends TestCase
{
    /**
     *
     *
     * @return array
     */
    public static function serviceDataProvider(): array
    {
        return [
            HashStr::class => [
                '$serviceId' => HashStr::class,
                '$expectedServiceClass' => HashStr::class
            ],
            AppConfig::class => [
                'serviceId' => AppConfig::class,
                'expectedServiceClass' => AppConfig::class
            ],
            TextDocumentAdministrationController::class => [
                'serviceId' => TextDocumentAdministrationController::class,
                'expectedServiceClass' => TextDocumentAdministrationController::class
            ],
            LoginController::class => [
                'serviceId' => LoginController::class,
                'expectedServiceClass' => LoginController::class
            ],
            LoggerInterface::class => [
                'serviceId' => LoggerInterface::class,
                'expectedServiceClass' => Logger::class
            ],
            CreateRegisterMagazinesController::class => [
                'serviceId' => CreateRegisterMagazinesController::class,
                'expectedServiceClass' => CreateRegisterMagazinesController::class
            ],
            CreateRegisterBooksController::class => [
                'serviceId' => CreateRegisterBooksController::class,
                'expectedServiceClass' => CreateRegisterBooksController::class
            ],
            UpdateMoveToArchiveBooksController::class => [
                'serviceId' => UpdateMoveToArchiveBooksController::class,
                'expectedServiceClass' => UpdateMoveToArchiveBooksController::class
            ],
            GetAuthorsCollectionController::class => [
                'serviceId' => GetAuthorsCollectionController::class,
                'expectedServiceClass' => GetAuthorsCollectionController::class
            ],
            GetAuthorsController::class => [
                'serviceId' => GetAuthorsController::class,
                'expectedServiceClass' => GetAuthorsController::class
            ],
            GetBooksCollectionController::class => [
                'serviceId' => GetBooksCollectionController::class,
                'expectedServiceClass' => GetBooksCollectionController::class
            ],
            GetBooksController::class => [
                'serviceId' => GetBooksController::class,
                'expectedServiceClass' => GetBooksController::class
            ],
            FindAuthors::class => [
                'serviceId' => FindAuthors::class,
                'expectedServiceClass' => FindAuthors::class
            ],
            FindBooks::class => [
                'serviceId' => FindBooks::class,
                'expectedServiceClass' => FindBooks::class
            ],
            DefaultRouter::class => [
                'serviceId' => DefaultRouter::class,
                'expectedServiceClass' => DefaultRouter::class
            ],
            RegExpRouter::class => [
                'serviceId' => RegExpRouter::class,
                'expectedServiceClass' => RegExpRouter::class
            ],
            UniversalRouter::class => [
                'serviceId' => UniversalRouter::class,
                'expectedServiceClass' => UniversalRouter::class
            ],
            RouterInterface::class => [
                'serviceId' => RouterInterface::class,
                'expectedServiceClass' => ChainRouters::class
            ],
            RenderInterface::class => [
                'serviceId' => RenderInterface::class,
                'expectedServiceClass' => DefaultRender::class
            ]

        ];
    }
    /** Проверяет корректность создания сервиса через di контейнер
     *
     * @dataProvider serviceDataProvider
     * @runInSeparateProcess
     * @param string $serviceId
     * @param string $expectedServiceClass
     * @throws Exception
     */
    public function testCreateService(string $serviceId, string $expectedServiceClass): void
    {
        //Arrange
        $diContainerFactory = new SymfonyDiContainerInit(
            new SymfonyDiContainerInit\ContainerParams(
                __DIR__ . '/../config/dev/di.xml',
                [
                    'kernel.project_dir' => __DIR__ . '/../'
                ],
                ContainerExtensions::httpAppContainerExtension()
            )
        );
        $diContainer = $diContainerFactory();

        //Act
        $actualService = $diContainer->get($serviceId);

        //Assert
        $this->assertInstanceOf($expectedServiceClass, $actualService);
    }
}
