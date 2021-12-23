<?php

require_once __DIR__ . '/../src/Infrastructure/Autoloader.php';

use EfTech\BookLibrary\Infrastructure\AppConfig;
use EfTech\BookLibrary\Infrastructure\Autoloader;
spl_autoload_register(new Autoloader([
        'EfTech\\BookLibrary\\' => __DIR__ . '/../src/',
        'EfTech\\BookLibraryTest\\' => __DIR__ . '/../test/'
    ])
);
use EfTech\BookLibrary\Infrastructure\App;
use EfTech\BookLibrary\Infrastructure\DI\Container;
use EfTech\BookLibrary\Infrastructure\http\ServerRequestFactory;
use EfTech\BookLibrary\Infrastructure\Logger\LoggerInterface;
use EfTech\BookLibrary\Infrastructure\View\RenderInterface;



$httpResponse = (new App(
    static function(Container $di):array {return $di->get('handlers');},
    static function(Container $di):LoggerInterface {return $di->get(LoggerInterface::class);},
    static function(Container $di):AppConfig {return $di->get(AppConfig::class);},
    static function(Container $di):RenderInterface {return $di->get(RenderInterface::class);},
    static function():Container {return Container::createFromArray(require __DIR__ . '/../config/dev/di.php');}
))->dispath(ServerRequestFactory::createFromGlobals($_SERVER));