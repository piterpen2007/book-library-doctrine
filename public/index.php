<?php

require_once __DIR__ . '/../src/Infrastructure/Autoloader.php';
require_once __DIR__ . '/../src/Infrastructure/app.function.php';
use EfTech\BookLibrary\Infrastructure\Autoloader;
spl_autoload_register(new Autoloader([
        'EfTech\\BookLibrary\\' => __DIR__ . '/../src/',
        'EfTech\\BookLibraryTest\\' => __DIR__ . '/../test/'
    ])
);
use EfTech\BookLibrary\Infrastructure\AppConfig;
use EfTech\BookLibrary\Infrastructure\App;
use EfTech\BookLibrary\Infrastructure\http\ServerRequestFactory;

use function EfTech\BookLibrary\Infrastructure\render;


$httpResponse = (new App(
    include __DIR__ . '/../config/request.handlers.php',
    'EfTech\BookLibrary\Infrastructure\Logger\Factory::create',
    static function() {return AppConfig::createFromArray(include __DIR__ . '/../config/dev/config.php');}
))->dispath(ServerRequestFactory::createFromGlobals($_SERVER,));

render($httpResponse);
