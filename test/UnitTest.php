<?php
require_once __DIR__ . '/../src/Infrastructure/Autoloader.php';

use EfTech\BookLibrary\Infrastructure\App;
use EfTech\BookLibrary\Infrastructure\AppConfig;
use EfTech\BookLibrary\Infrastructure\Autoloader;
use EfTech\BookLibrary\Infrastructure\DI\Container;
use EfTech\BookLibrary\Infrastructure\http\ServerRequest;
use EfTech\BookLibrary\Infrastructure\Logger\LoggerInterface;
use EfTech\BookLibrary\Infrastructure\Router\RouterInterface;
use EfTech\BookLibrary\Infrastructure\Uri\Uri;
use EfTech\BookLibrary\Infrastructure\View\NullRender;
use EfTech\BookLibrary\Infrastructure\View\RenderInterface;
use EfTech\BookLibraryTest\TestUtils;


spl_autoload_register(
    new Autoloader([
        'EfTech\\BookLibrary\\' => __DIR__ . '/../src/',
        'EfTech\\BookLibraryTest\\' => __DIR__ . '/../test/'
    ])
);

/**
 *  Тестирование приложения
 */
class UnitTest
{
    private static function testDataProvider():array
    {
        $diConfig = require __DIR__ . '/../config/dev/di.php';
        $diConfig['services'][\EfTech\BookLibrary\Infrastructure\Logger\AdapterInterface::class] = [
            'class' => \EfTech\BookLibrary\Infrastructure\Logger\Adapter\NullAdapter::class
        ];
        $diConfig['services'][RenderInterface::class] = [
            'class' => NullRender::class
        ];


          return [
//            [
//                'testName'=>'Тестирование поиска книг по названию',
//                'in' => [
//                    'handlers' => $handlers,
//                    'uri' => '/books?title=Мечтают ли андроиды об электроовцах?',
//                    'loggerFactory' => 'EfTech\BookLibrary\Infrastructure\Logger\Factory::create',
//                    'appConfigFactory' => static function (){
//                        $config = include __DIR__ . '/../config/dev/config.php';
//                        $config['loggerType'] = 'echoLogger';
//                        return AppConfig::createFromArray($config);
//                    }
//                ],
//                'out' => [
//                    'httpCode' => 200,
//                    'result' => [
//                        [
//                        'id' => 10,
//                        'title' => 'Мечтают ли андроиды об электроовцах?',
//                        'year' => 1966,
//                        'title_for_printing' => 'Мечтают ли андроиды об электроовцах? . Дик Филип . 1966',
//                        'author' =>
//                            [
//                                'id' => 5,
//                                'name' => 'Филип',
//                                'surname' => 'Дик',
//                                'birthday' => '16.12.1928',
//                                'country' => 'us',
//                            ],
//                        ]
//                    ],
//                ]
//            ],
            [
                'testName' => 'Тестирование ситуации когда данные о книгах не корректны. Нет поля year',
                'in' => [
                    'uri' => '/books?title=Мечтают ли андроиды об электроовцах?',
                    'diConfig' => (static function($diConfig) {
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
            [
                'testName' => 'Тестирование ситуации с некорректным  данными конфига приложения',
                'in' => [
                    'uri' => '/books?title=Мечтают ли андроиды об электроовцах?',
                    'diConfig' => (static function($diConfig) {
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
            [
                'testName' => 'Тестирование ситуации с некорректным путем до файла с книгами',
                'in' => [
                    'uri' => '/books?title=Мечтают ли андроиды об электроовцах?',
                    'diConfig' => (static function($diConfig) {
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
            [
                'testName' => 'Тестирование ситуации когда данные о журналах некорректны. Нет поля id',
                'in' => [
                    'uri' =>'/books?title=National Geographic Magazine',
                    'diConfig' => (static function($diConfig) {
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
            ,

        ],
            [
                'testName' => 'Тестирование ситуации когда данные в авторах некорректны. Нет поля birthday',
                'in' => [
                    'uri' => '/books?title=Мечтают ли андроиды об электроовцах?',
                    'diConfig' => (static function($diConfig) {
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
            [
                'testName' => 'Тестирование ситуации с некорректным путем до файла о авторе',
                'in' => [
                    'uri' => '/books?title=Мечтают ли андроиды об электроовцах?',
                    'diConfig' => (static function($diConfig) {
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
            [
                'testName' => 'Тестирование ситуации с некорректным путем до файла до журналов',
                'in' => [
                    'uri' =>  '/books?title=Мечтают ли андроиды об электроовцах?',
                    'diConfig' => (static function($diConfig) {
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
            ],
            [
                'testName'=>'Тестирование поиска книг по названию',
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
                    ],
                ]
            ],
        ];
    }
    /**
     * Запускает тест
     *
     * @return void
     */
    public static function runTest():void
    {
        foreach (static::testDataProvider() as $testItem) {
            echo "-----{$testItem['testName']}-----\n";

            $httpRequest = new ServerRequest(
                'GET',
                '1.1',
                $testItem['in']['uri'],
                Uri::createFromString($testItem['in']['uri']),
                ['Content-Type' => 'application/json'],
                null
            );
            //Arrange и Act
            $diConfig = $testItem['in']['diConfig'];
            $httpResponse = (new App(
                static function(Container $di): RouterInterface {return $di->get(RouterInterface::class);},
                static function(Container $di):LoggerInterface {return $di->get(LoggerInterface::class);},
                static function(Container $di):AppConfig {return $di->get(AppConfig::class);},
                static function(Container $di):RenderInterface {return $di->get(RenderInterface::class);},
                static function() use($diConfig) :Container {return Container::createFromArray($diConfig);}
            ))->dispath($httpRequest);


            //Assert
            if ($httpResponse->getStatusCode() === $testItem['out']['httpCode']) {
                echo "    OK --- код ответа\n";
            } else {
                echo "    FAIL - код ответа. Ожидалось: {$testItem['out']['httpCode']}. Актуальное значение: {$httpResponse->getStatusCode()}\n";
            }

            $actualResult =  json_decode($httpResponse->getBody(), true, 512 , JSON_THROW_ON_ERROR);

            $unnecessaryElements = TestUtils::arrayDiffAssocRecursive($actualResult, $testItem['out']['result']);
            $missingElements =  TestUtils::arrayDiffAssocRecursive($testItem['out']['result'], $actualResult);

            $errMsg = '';

            if (count($unnecessaryElements) > 0) {
                $errMsg .= sprintf("         Есть лишние элементы %s\n", json_encode($unnecessaryElements,JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE));
            }
            if (count($missingElements) > 0) {
                $errMsg .= sprintf("         Есть лишние недостающие элементы %s\n", json_encode($missingElements,JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE));
            }

            if ('' === $errMsg) {
                echo "    ОК- данные ответа валидны\n";
            } else {
                echo "    FAIL - данные ответа валидны\n" . $errMsg;
            }


        }
    }
}
UnitTest::runTest();