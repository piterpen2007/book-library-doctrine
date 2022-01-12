<?php

use EfTech\BookLibrary;
use EfTech\BookLibrary\ConsoleCommand\FindAuthors;
use EfTech\BookLibrary\ConsoleCommand\FindBooks;
use EfTech\BookLibrary\Controller\UpdateMoveToArchiveBooksController;
use EfTech\BookLibrary\Infrastructure\AppConfig;
use EfTech\BookLibrary\Infrastructure\Console\Output\EchoOutput;
use EfTech\BookLibrary\Infrastructure\Console\Output\OutputInterface;
use EfTech\BookLibrary\Infrastructure\DataLoader\DataLoaderInterface;
use EfTech\BookLibrary\Infrastructure\DataLoader\JsonDataLoader;
use EfTech\BookLibrary\Infrastructure\DI\ContainerInterface;
use EfTech\BookLibrary\Infrastructure\Logger\FileLogger\Logger;
use EfTech\BookLibrary\Infrastructure\Logger\LoggerInterface;
use EfTech\BookLibrary\Infrastructure\Router\ChainRouters;
use EfTech\BookLibrary\Infrastructure\Router\ControllerFactory;
use EfTech\BookLibrary\Infrastructure\Router\DefaultRouter;
use EfTech\BookLibrary\Infrastructure\Router\RegExpRouter;
use EfTech\BookLibrary\Infrastructure\Router\RouterInterface;
use EfTech\BookLibrary\Infrastructure\Router\UniversalRouter;
use EfTech\BookLibrary\Repository\TextDocumentJsonFileRepository;
use EfTech\BookLibrary\Service\SearchAuthorsService;
use EfTech\BookLibrary\Service\SearchTextDocumentService;

return [
    'instances' => [
        'handlers' => require __DIR__ . '/../request.handlers.php',
        'regExpHandlers' => require __DIR__ . '/../regExp.handlers.php',
        'appConfig' => require __DIR__ . '/config.php',
        'controllerNs' => 'EfTech\\BookLibrary\\Controller'
    ],
    'services' => [
        BookLibrary\Entity\AuthorRepositoryInterface::class => [
            'class' => BookLibrary\Repository\AuthorJsonFileRepository::class,
            'args' => [
                'pathToAuthor' => 'pathToAuthor',
                'dataLoader' => \EfTech\BookLibrary\Infrastructure\DataLoader\DataLoaderInterface::class
            ]

        ],
        BookLibrary\Entity\TextDocumentRepositoryInterface::class => [
            'class' => TextDocumentJsonFileRepository::class,
            'args' => [
                'pathToBooks' => 'pathToBooks',
                'pathToMagazines' => 'pathToMagazines',
                'pathToAuthor' => 'pathToAuthor',
                'dataLoader' => \EfTech\BookLibrary\Infrastructure\DataLoader\DataLoaderInterface::class
            ]
        ],
        UpdateMoveToArchiveBooksController::class => [
            'args' => [
                'archivingTextDocumentService' => BookLibrary\Service\ArchivingTextDocumentService::class

            ]
        ],
        BookLibrary\Service\ArchivingTextDocumentService::class => [
            'args' => [
                'textDocumentRepository' => BookLibrary\Entity\TextDocumentRepositoryInterface::class
            ]
        ],
        SearchAuthorsService::class => [
            'args' => [
                'logger' => \EfTech\BookLibrary\Infrastructure\Logger\LoggerInterface::class,
                'authorRepository' => BookLibrary\Entity\AuthorRepositoryInterface::class
            ]
        ],
        \EfTech\BookLibrary\Controller\GetAuthorsCollectionController::class => [
            'args' => [
                'logger' => \EfTech\BookLibrary\Infrastructure\Logger\LoggerInterface::class,
                'searchAuthorsService' => SearchAuthorsService::class,
            ]
        ],
        \EfTech\BookLibrary\Controller\GetAuthorsController::class => [
            'args' => [
                'logger' => \EfTech\BookLibrary\Infrastructure\Logger\LoggerInterface::class,
                'searchAuthorsService' => SearchAuthorsService::class,
            ]
        ],


        DataLoaderInterface::class => [
            'class' => JsonDataLoader::class
        ],
        SearchTextDocumentService::class => [
            'args' => [
                'logger' => \EfTech\BookLibrary\Infrastructure\Logger\LoggerInterface::class,
                'searchDocumentRepository' => BookLibrary\Entity\TextDocumentRepositoryInterface::class
            ]
        ],
        OutputInterface::class => [
            'class' => EchoOutput::class,
            'args' => [

            ]
        ],
        FindAuthors::class => [
            'args' => [
                'output' => OutputInterface::class,
                'SearchAuthorsService' => SearchAuthorsService::class,
            ]

        ],
        FindBooks::class => [
            'args' => [
                'output' => OutputInterface::class,
                'searchTextDocumentService' => SearchTextDocumentService::class,
            ]
        ],
        BookLibrary\Controller\GetBooksCollectionController::class => [
            'args' => [
                'logger' => LoggerInterface::class,
                'searchTextDocumentService' => SearchTextDocumentService::class,
            ]

        ],
        BookLibrary\Controller\GetBooksController::class => [
            'args' => [
                'logger' => LoggerInterface::class,
                'searchTextDocumentService' => SearchTextDocumentService::class,
            ]

        ],
       // BookLibrary\Controller\GetAuthorsController::class => [
        //    'args' => [
        //        'pathToAuthor' => 'pathToAuthor',
        //        'logger' => LoggerInterface::class
        //    ]
       // ],
       // GetAuthorsCollectionController::class => [
        //    'args' => [
        //        'pathToAuthor' => 'pathToAuthor',
        //        'logger' => LoggerInterface::class
        //    ]
        //],
        LoggerInterface::class => [
            'class' => Logger::class,
            'args' => [
                'pathToFile' => 'pathToLogFile'
            ]
        ],
        BookLibrary\Infrastructure\View\RenderInterface::class => [
            'class' => BookLibrary\Infrastructure\View\DefaultRender::class
        ],
        RouterInterface::class => [
            'class' => ChainRouters::class,
            'args' => [
                RegExpRouter::class,
                DefaultRouter::class,
                UniversalRouter::class
            ]
        ],
        UniversalRouter::class => [
            'args' => [
                'ControllerFactory' => ControllerFactory::class,
                'controllerNs' => 'controllerNs'
            ]
        ],
        DefaultRouter::class => [
            'args' => [
                'handlers' => 'handlers',
                'controllerFactory' => ControllerFactory::class
            ]
        ],
        ControllerFactory::class => [
            'args' => [
                'diContainer' => ContainerInterface::class
            ]
        ],
        RegExpRouter::class => [
            'args' => [
                'handlers' => 'regExpHandlers',
                'controllerFactory' => ControllerFactory::class
            ]
        ]
    ],
'factories'=>[
        ContainerInterface::class => static function(ContainerInterface $c):ContainerInterface {
            return $c;
        },
        'pathToLogFile' => static function(ContainerInterface $c):string {
            /** @var AppConfig $appConfig */
            $appConfig = $c->get(AppConfig::class);
            return $appConfig->getPathToLogFile();
        },
        'pathToBooks' => static function(ContainerInterface $c):string {
            /** @var AppConfig $appConfig */
            $appConfig = $c->get(AppConfig::class);
            return $appConfig->getPathToBooks();
        },
        'pathToMagazines' => static function(ContainerInterface $c):string {
            /** @var AppConfig $appConfig */
            $appConfig = $c->get(AppConfig::class);
            return $appConfig->getPathToMagazines();
        },
        'pathToAuthor' => static function(ContainerInterface $c):string {
            /** @var AppConfig $appConfig */
            $appConfig = $c->get(AppConfig::class);
            return $appConfig->getPathToAuthor();
        },
        AppConfig::class => static function(ContainerInterface $c): AppConfig {
            $appConfig = $c->get('appConfig');
            return AppConfig::createFromArray($appConfig);
        }
    ],
];