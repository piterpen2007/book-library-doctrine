<?php

require_once __DIR__ . '/../src/Infrastructure/Autoloader.php';
use EfTech\BookLibrary\Infrastructure\Autoloader;
spl_autoload_register(new Autoloader([
        'EfTech\\BookLibrary\\' => __DIR__ . '/../src/',
        'EfTech\\BookLibraryTest\\' => __DIR__ . '/../test/'
    ])
);
use EfTech\BookLibrary\Infrastructure\AppConfig;
use EfTech\BookLibrary\Infrastructure\App;
use EfTech\BookLibrary\Infrastructure\DI\ServiceLocator;
use EfTech\BookLibrary\Infrastructure\http\ServerRequestFactory;
use EfTech\BookLibrary\Infrastructure\Logger\Factory;
use EfTech\BookLibrary\Infrastructure\Logger\LoggerInterface;
use EfTech\BookLibrary\Infrastructure\View\DefaultRender;
use EfTech\BookLibrary\Infrastructure\View\RenderInterface;


$httpResponse = (static function() {
    $appConfig = AppConfig::createFromArray(include __DIR__ . '/../config/dev/config.php');
    $serviceInstances = [
        'handlers' => require __DIR__ . '/../config/request.handlers.php',
        LoggerInterface::class => Factory::create($appConfig),
        AppConfig::class => $appConfig,
        RenderInterface::class => new DefaultRender()
    ];
    $sl = new ServiceLocator($serviceInstances);
    return new App($sl);
})()->dispath(ServerRequestFactory::createFromGlobals($_SERVER));

