#!/usr/nin/env_php
<?php
require_once __DIR__ . '/../vendor/autoload.php';

use EfTech\BookLibrary\Infrastructure\Console\AppConsole;
use EfTech\BookLibrary\Infrastructure\Console\Output\OutputInterface;
use EfTech\BookLibrary\Infrastructure\DI\Container;
use EfTech\BookLibrary\Infrastructure\DI\ContainerInterface;
use EfTech\BookLibrary\Infrastructure\DI\SymfonyDiContainerInit;


(new AppConsole(
    require __DIR__ . '/../config/console.handlers.php',
    static function (ContainerInterface $di): OutputInterface {
        return $di->get(OutputInterface::class);
    },
    new SymfonyDiContainerInit(
        new SymfonyDiContainerInit\ContainerParams(
            __DIR__ . '/../config/dev/di.xml',
            [
                'kernel.project_dir' => __DIR__ . '/../'
            ],
            \EfTech\BookLibrary\Config\ContainerExtensions::consoleContainerExtension()
        ),
        new SymfonyDiContainerInit\CacheParams(
            'DEV' !== getenv('ENV_TYPE'),
            __DIR__ . '/../var/cache/di-symfony/EfTechBookLibraryCachedContainer.php'
        )
    )
))->dispatch();
