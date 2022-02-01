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
        __DIR__ . '/../config/dev/di.xml',
        [
                'kernel.project_dir' => __DIR__ . '/../'
            ]
    )
))->dispatch();
