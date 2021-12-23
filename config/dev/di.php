<?php

use EfTech\BookLibrary;
use EfTech\BookLibrary\Controller\FindAuthors;
use EfTech\BookLibrary\Infrastructure\AppConfig;
use EfTech\BookLibrary\Infrastructure\DI\ContainerInterface;
use EfTech\BookLibrary\Infrastructure\Logger\FileLogger\Logger;
use EfTech\BookLibrary\Infrastructure\Logger\LoggerInterface;

return [
    'instances' => [
        'handlers' => require __DIR__ . '/../request.handlers.php',
        'appConfig' => require __DIR__ . '/config.php'
    ],
    'services' => [
        BookLibrary\Controller\FindBooks::class => [
            'args' => [
                'appConfig' => AppConfig::class,
                'logger' => LoggerInterface::class
            ]
        ],
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
        ],
        BookLibrary\Infrastructure\View\RenderInterface::class => [
            'class' => BookLibrary\Infrastructure\View\DefaultRender::class
        ]
    ],


    'factories'=>[
        'pathToLogFile' => static function(ContainerInterface $c):string {
            /** @var AppConfig $appConfig */
            $appConfig = $c->get(AppConfig::class);
            return $appConfig->getPathToLogFile();
        },
        AppConfig::class => static function(ContainerInterface $c): AppConfig {
            $appConfig = $c->get('appConfig');
            return AppConfig::createFromArray($appConfig);
        }
    ],
];