#!/usr/nin/env_php
<?php

use EfTech\BookLibrary\Infrastructure\Autoloader\Autoloader;
use EfTech\BookLibrary\Infrastructure\Console\AppConsole;
use EfTech\BookLibrary\Infrastructure\Console\Output\OutputInterface;
use EfTech\BookLibrary\Infrastructure\DI\Container;
use EfTech\BookLibrary\Infrastructure\DI\ContainerInterface;

require_once __DIR__ . '/../src/Infrastructure/Autoloader/Autoloader.php';

spl_autoload_register(new Autoloader([
        'EfTech\\BookLibrary\\' => __DIR__ . '/../src/',
        'EfTech\\BookLibraryTest\\' => __DIR__ . '/../test/'
    ])
);

(new AppConsole(
    require __DIR__ . '/../config/console.handlers.php',
    static function(ContainerInterface $di):OutputInterface {
        return $di->get(OutputInterface::class);
    },
    static function():ContainerInterface {
        return Container::createFromArray(require __DIR__ . '/../config/dev/di.php');
    }
))->dispatch();