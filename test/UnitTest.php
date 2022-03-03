<?php

namespace EfTech\BookLibraryTest;

use EfTech\BookLibrary\Config\AppConfig;
use EfTech\BookLibrary\Config\ContainerExtensions;
use EfTech\BookLibrary\Infrastructure\DI\ContainerInterface;
use EfTech\BookLibrary\Infrastructure\DI\SymfonyDiContainerInit;
use EfTech\BookLibrary\Infrastructure\HttpApplication\App;
use EfTech\BookLibrary\Infrastructure\Logger\Adapter\NullAdapter;
use EfTech\BookLibrary\Infrastructure\Logger\AdapterInterface;
use Psr\Log\LoggerInterface;
use EfTech\BookLibrary\Infrastructure\Router\RouterInterface;
use EfTech\BookLibrary\Infrastructure\View\NullRender;
use EfTech\BookLibrary\Infrastructure\View\RenderInterface;
use Exception;
use JsonException;
use Nyholm\Psr7\Uri;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 *  Тестирование приложения
 */
class UnitTest extends TestCase
{
    public static function bugFactory(array $config): string
    {
        return 'Ops!';
    }
    /**
     * Создаёт DI контайнер симфони
     * @throws Exception
     */
    private static function createDiContainer(): ContainerBuilder
    {
        $containerBuilder = SymfonyDiContainerInit::createContainerBuilder(
            new SymfonyDiContainerInit\ContainerParams(
                __DIR__ . '/../config/dev/di.xml',
                [
                    'kernel.project_dir' => __DIR__ . '/../'
                ],
                ContainerExtensions::httpAppContainerExtension()
            )
        );

        $containerBuilder->removeAlias(LoggerInterface::class);
        $containerBuilder->setDefinition(NullLogger::class, new Definition());
        $containerBuilder->setAlias(LoggerInterface::class, NullLogger::class)->setPublic(true);

        //$containerBuilder->setAlias(AdapterInterface::class, NullAdapter::class);



        $containerBuilder->getDefinition(RenderInterface::class)
            ->setClass(NullRender::class)
            ->setArguments([]);
        return $containerBuilder;
    }

    /** Поставщик данных для тестирования приложения
     * @return array
     * @throws Exception
     */
    public static function dataProvider(): array
    {
        return [
            'Тестирование поиска книг по названию' => [
                'in' => [
                    'uri' => '/books?title=Мечтают ли андроиды об электроовцах?',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                            $c->compile();
                            return $c;
                    })(self::createDiContainer())

                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id' => 10,
                            'title' => 'Мечтают ли андроиды об электроовцах?',
                            'year' => 1966,
                            'title_for_printing' => 'Мечтают ли андроиды об электроовцах? . Дик Филип . 1966',
                            'author' =>
                                [
                                    [
                                    'id' => 5,
                                    'name' => 'Филип',
                                    'surname' => 'Дик',
                                    'birthday' => '16.12.1928',
                                    'country' => 'us',
                                    ]
                                ]
                        ]
                    ]
                ]
            ],
            'Тестирование ситуации с некорректным  данными конфига приложения' => [
                'in' => [
                    'uri' => '/books?title=Мечтают ли андроиды об электроовцах?',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->getDefinition(AppConfig::class)->setFactory([UnitTest::class, 'bugFactory']);
                        $c->compile();
                        return $c;
                    })(self::createDiContainer())

                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'system error'
                    ]
                ]
            ]
        ];
    }

    /** Запускает тест
     * @param array $in - входные данные
     * @param array $out
     * @dataProvider  dataProvider
     * @throws JsonException
     */
    public function testApp(array $in, array $out): void
    {
        $httpRequest = new \Nyholm\Psr7\ServerRequest(
            'GET',
            new Uri($in['uri']),
            ['Content-Type' => 'application/json']
        );
        $queryParams = [];
        parse_str($httpRequest->getUri()->getQuery(), $queryParams);
        $httpRequest = $httpRequest->withQueryParams($queryParams);

        //Arrange и Act
        $diContainer = $in['diContainer'];
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
            static function () use ($diContainer): ContainerInterface {
                return $diContainer;
            }
        ))->dispath($httpRequest);
        // Assert
        $this->assertEquals($out['httpCode'], $httpResponse->getStatusCode(), 'код ответа');
        $this->assertEquals(
            $out['result'],
            $actualResult =  json_decode($httpResponse->getBody(), true, 512, JSON_THROW_ON_ERROR),
            'структура ответа'
        );
    }
}
