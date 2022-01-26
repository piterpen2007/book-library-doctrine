<?php
require_once __DIR__ . '/../vendor/autoload.php';

use EfTech\BookLibrary\Config\AppConfig;
use EfTech\BookLibrary\Infrastructure\HttpApplication\App;
use EfTech\BookLibrary\Infrastructure\DI\Container;
use EfTech\BookLibrary\Infrastructure\http\ServerRequestFactory;
use EfTech\BookLibrary\Infrastructure\Logger\LoggerInterface;
use EfTech\BookLibrary\Infrastructure\Router\RouterInterface;
use EfTech\BookLibrary\Infrastructure\View\RenderInterface;


$httpResponse = (new App(
    static function(Container $di):RouterInterface {return $di->get(RouterInterface::class);},
    static function(Container $di):LoggerInterface {return $di->get(LoggerInterface::class);},
    static function(Container $di):AppConfig {return $di->get(AppConfig::class);},
    static function(Container $di):RenderInterface {return $di->get(RenderInterface::class);},
    static function():Container {return Container::createFromArray(require __DIR__ . '/../config/dev/di.php');}
))->dispath(ServerRequestFactory::createFromGlobals($_SERVER,file_get_contents('php://input')));