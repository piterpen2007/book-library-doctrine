<?php

namespace EfTech\BookLibraryTest;

use EfTech\BookLibrary\Config\AppConfig;
use EfTech\BookLibrary\Infrastructure\DI\Container;
use EfTech\BookLibrary\Infrastructure\DI\ContainerInterface;
use EfTech\BookLibrary\Infrastructure\http\ServerRequest;
use EfTech\BookLibrary\Infrastructure\HttpApplication\App;
use EfTech\BookLibrary\Infrastructure\Logger\LoggerInterface;
use EfTech\BookLibrary\Infrastructure\Router\RouterInterface;
use EfTech\BookLibrary\Infrastructure\Uri\Uri;
use EfTech\BookLibrary\Infrastructure\View\NullRender;
use EfTech\BookLibrary\Infrastructure\View\RenderInterface;
use PHPUnit\Framework\TestCase;

/**
 *  Тестирование приложения
 */
class UnitTest extends TestCase
{
    /** Поставщик данных для тестирования приложения
     * @return array
     */
    public static function dataProvider(): array
    {
        $diConfig = require __DIR__ . '/../config/dev/di.php';
        $diConfig['services'][\EfTech\BookLibrary\Infrastructure\Logger\AdapterInterface::class] = [
            'class' => \EfTech\BookLibrary\Infrastructure\Logger\Adapter\NullAdapter::class
        ];
        $diConfig['services'][RenderInterface::class] = [
            'class' => NullRender::class
        ];

        return [
            'Тестирование поиска книг по названию' => [
                'in' => [
                    'uri' => '/books?title=Мечтают ли андроиды об электроовцах?',
                    'diConfig' => $diConfig

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
                                    'id' => 5,
                                    'name' => 'Филип',
                                    'surname' => 'Дик',
                                    'birthday' => '16.12.1928',
                                    'country' => 'us',
                                ],
                        ]
                    ]
                ]
            ],
            'Тестирование ситуации когда данные о книгах не корректны. Нет поля year' => [
                'in' => [
                    'uri' => '/books?title=Мечтают ли андроиды об электроовцах?',
                    'diConfig' => (static function ($diConfig) {
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['pathToBooks'] = __DIR__ . '/data/broken.books.json';
                        $diConfig['instances']['appConfig'] = $config;
                        return $diConfig;
                    })($diConfig)
                ],
                'out' => [
                    'httpCode' => 503,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Отсутствуют обязательные элементы: year'
                    ]
                ]
            ],
            'Тестирование ситуации с некорректным  данными конфига приложения' => [
                'in' => [
                    'uri' => '/books?title=Мечтают ли андроиды об электроовцах?',
                    'diConfig' => (static function ($diConfig) {
                        $diConfig['factories'][AppConfig::class] = static function () {
                            return 'Ops!';
                        };
                        return $diConfig;
                    })($diConfig)

                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'system error'
                    ]
                ]
            ],
            'Тестирование ситуации с некорректным путем до файла с книгами' => [
                'in' => [
                    'uri' => '/books?title=Мечтают ли андроиды об электроовцах?',
                    'diConfig' => (static function ($diConfig) {
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['pathToBooks'] = __DIR__ . '/data/unknown.books.json';
                        $diConfig['instances']['appConfig'] = $config;
                        return $diConfig;
                    })($diConfig)
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Некорректный путь до файла с данными'
                    ]
                ]
            ],
            'Тестирование ситуации когда данные о журналах некорректны. Нет поля id' => [
                'in' => [
                    'uri' => '/books?title=National Geographic Magazine',
                    'diConfig' => (static function ($diConfig) {
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['pathToMagazines'] = __DIR__ . '/data/broken.magazines.json';
                        $diConfig['instances']['appConfig'] = $config;
                        return $diConfig;
                    })($diConfig)
                ],
                'out' => [
                    'httpCode' => 503,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Нету id текстового документа'
                    ]
                ]
            ],
            'Тестирование ситуации когда данные в авторах некорректны. Нет поля birthday' => [
                'in' => [
                    'uri' => '/books?title=Мечтают ли андроиды об электроовцах?',
                    'diConfig' => (static function ($diConfig) {
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['pathToAuthor'] = __DIR__ . '/data/broken.authors.json';
                        $diConfig['instances']['appConfig'] = $config;
                        return $diConfig;
                    })($diConfig)
                ],
                'out' => [
                    'httpCode' => 503,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Отсутствуют обязательные элементы: birthday'
                    ]
                ]
            ],
            'Тестирование ситуации с некорректным путем до файла о авторе' => [
                'in' => [
                    'uri' => '/books?title=Мечтают ли андроиды об электроовцах?',
                    'diConfig' => (static function ($diConfig) {
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['pathToAuthor'] = __DIR__ . '/data/unknown.authors.json';
                        $diConfig['instances']['appConfig'] = $config;
                        return $diConfig;
                    })($diConfig)
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Некорректный путь до файла с данными'
                    ]
                ]
            ],
            'Тестирование ситуации с некорректным путем до файла до журналов' => [
                'in' => [
                    'uri' =>  '/books?title=Мечтают ли андроиды об электроовцах?',
                    'diConfig' => (static function ($diConfig) {
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['pathToMagazines'] = __DIR__ . '/data/unknown.magazines.json';
                        $diConfig['instances']['appConfig'] = $config;
                        return $diConfig;
                    })($diConfig)

                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Некорректный путь до файла с данными'
                    ]
                ]
            ]
        ];
    }

    /** Запускает тест
     * @param array $in - входные данные
     * @param array $out
     * @dataProvider  dataProvider
     * @throws \JsonException
     */
    public function testApp(array $in, array $out): void
    {
        $httpRequest = new ServerRequest(
            'GET',
            '1.1',
            $in['uri'],
            Uri::createFromString($in['uri']),
            ['Content-Type' => 'application/json'],
            null
        );
        //Arrange и Act
        $diConfig = $in['diConfig'];
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
            static function () use ($diConfig): ContainerInterface {
                return Container::createFromArray($diConfig);
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
