#!/usr/nin/env_php
<?php
require_once __DIR__ . '/../vendor/autoload.php';

use EfTech\BookLibrary\Infrastructure\Console\AppConsole;
use EfTech\BookLibrary\Infrastructure\Console\Output\OutputInterface;
use EfTech\BookLibrary\Infrastructure\DI\Container;
use EfTech\BookLibrary\Infrastructure\DI\ContainerInterface;


(new AppConsole(
    require __DIR__ . '/../config/console.handlers.php',
    static function(ContainerInterface $di):OutputInterface {
        return $di->get(OutputInterface::class);
    },
    static function():ContainerInterface {
        return Container::createFromArray(require __DIR__ . '/../config/dev/di.php');
    }
))->dispatch();