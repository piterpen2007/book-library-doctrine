<?php

require_once __DIR__ . '/../vendor/autoload.php';

use EfTech\BookLibrary\Config\AppConfig;
use EfTech\BookLibrary\Infrastructure\DI\ContainerInterface;
use EfTech\BookLibrary\Infrastructure\DI\SymfonyDiContainerInit;
use EfTech\BookLibrary\Infrastructure\HttpApplication\App;
use Psr\Log\LoggerInterface;
use EfTech\BookLibrary\Infrastructure\Router\RouterInterface;
use EfTech\BookLibrary\Infrastructure\View\RenderInterface;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Psr\Http\Message\ServerRequestInterface;


$httpResponse = (new App(
    static function (ContainerInterface $di): RouterInterface {
        return $di->get(RouterInterface::class);
    },
    static function (ContainerInterface $di): LoggerInterface {
        return $di->get(LoggerInterface::class);
    },
    static function (ContainerInterface $di): AppConfig {
        return $di->get(AppConfig::class);
    },
    static function (ContainerInterface $di): RenderInterface {
        return $di->get(RenderInterface::class);
    },
    new SymfonyDiContainerInit(
        new SymfonyDiContainerInit\ContainerParams(
            __DIR__ . '/../config/dev/di.xml',
            [
                'kernel.project_dir' => __DIR__ . '/../'
            ],
            \EfTech\BookLibrary\Config\ContainerExtensions::httpAppContainerExtension()
        ),
        new SymfonyDiContainerInit\CacheParams(
            'DEV' !== getenv('ENV_TYPE'),
            __DIR__ . '/../var/cache/di-symfony/EfTechBookLibraryCachedContainer.php'
        )
    )
))->dispath(
    (static function (): ServerRequestInterface {
        $psr17Factory = new Psr17Factory();

        $creator = new ServerRequestCreator(
            $psr17Factory, // ServerRequestFactory
            $psr17Factory, // UriFactory
            $psr17Factory, // UploadedFileFactory
            $psr17Factory  // StreamFactory
        );

        return $creator->fromGlobals();
    })()
);
