<?php

use EfTech\BookLibrary\Infrastructure\AppConfig;
use EfTech\BookLibrary\Infrastructure\Logger\LoggerInterface;
use EfTech\BookLibrary\Infrastructure\Logger\NullLogger\Logger;
use function EfTech\BookLibrary\Infrastructure\app;

require_once __DIR__ . '/../src/Infrastructure/AppConfig.php';
require_once __DIR__ . '/../src/Infrastructure/app.function.php';
require_once __DIR__ . '/../src/Infrastructure/Logger/LoggerInterface.php';
require_once __DIR__ . '/../src/Infrastructure/Logger/NullLogger/Logger.php';
require_once __DIR__ . '/../src/Infrastructure/Logger/Factory.php';

/** Вычисляет расскхождение массивов с доп проверкой индекса. Поддержка многомерных массивов
 * @param array $a1
 * @param array $a2
 * @return array
 */
function array_diff_assoc_recursive(array $a1,array $a2):array
{
    $result = [];
    foreach ($a1 as $k1 => $v1) {
        if(false === array_key_exists($k1, $a2)){
            $result[$k1] = $v1;
            continue;
        }
        if(is_iterable($v1) && is_iterable($a2[$k1])) {
            $resultCheck = array_diff_assoc_recursive($v1, $a2[$k1]);
            if (count($resultCheck) > 0 ) {
                $result[$k1] = $resultCheck;
            }
            continue;
        }
        if ($v1 !== $a2[$k1]) {
            $result[$k1] = $v1;
        }
    }
    return $result;
}
/**
 *  Тестирование приложения
 */
class UnitTest
{
    private static function testDataProvider():array
    {
        $handlers = include __DIR__ . '/../config/request.handlers.php';

        $loggerFactory = static function():LoggerInterface {return new Logger();};

        return [
            [
                'testName'=>'Тестирование поиска книг по названию',
                'in' => [
                    $handlers,
                    '/books?title=Мечтают ли андроиды об электроовцах?',
                    'EfTech\BookLibrary\Infrastructure\Logger\Factory::create',
                    static function (){
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['loggerType'] = 'echoLogger';
                        return AppConfig::createFromArray($config);
                    }
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
            [
                'testName' => 'Тестирование ситуации когда данные о книгах не корректны. Нет поля year',
                'in' => [
                    $handlers,
                    '/books?title=Мечтают ли андроиды об электроовцах?',
                    $loggerFactory,
                    static function (){
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['pathToBooks'] = __DIR__ . '/../test/data/broken.books.json';
                        return AppConfig::createFromArray($config);
                    }
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
                    $handlers,
                    '/books?title=Мечтают ли андроиды об электроовцах?',
                    $loggerFactory,
                    static function (){
                        return 'Ops!';
                    }
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'incorrect application config'
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование ситуации с некорректным путем до файла с книгами',
                'in' => [
                    $handlers,
                    '/books?title=Мечтают ли андроиды об электроовцах?',
                    $loggerFactory,
                    static function (){
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['pathToBooks'] = __DIR__ . '/data/unknown.books.json';
                        return AppConfig::createFromArray($config);
    }
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
                    $handlers,
                    '/books?title=National Geographic Magazine',
                    $loggerFactory,
                    static function (){
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['pathToMagazines'] = __DIR__ . '/../test/data/broken.magazines.json';
                        return AppConfig::createFromArray($config);
                    }
                ],
                'out' => [
                    'httpCode' => 503,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Отсутствуют обязательные элементы: id'
                    ]
                ]
            ,

        ],
            [
                'testName' => 'Тестирование ситуации когда данные в авторах некорректны. Нет поля birthday',
                'in' => [
                    $handlers,
                    '/books?title=Мечтают ли андроиды об электроовцах?',
                    $loggerFactory,
                    static function (){
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['pathToAuthor'] = __DIR__ . '/../test/data/broken.authors.json';
                        return AppConfig::createFromArray($config);
                    }
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
                    $handlers,
                    '/books?title=Мечтают ли андроиды об электроовцах?',
                    $loggerFactory,
                    static function (){
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['pathToAuthor'] = __DIR__ . '/data/unknown.authors.json';
                        return AppConfig::createFromArray($config);
                    }
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
                    $handlers,
                    '/books?title=Мечтают ли андроиды об электроовцах?',
                    $loggerFactory,
                    static function (){
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['pathToMagazines'] = __DIR__ . '/data/unknown.magazines.json';
                        return AppConfig::createFromArray($config);
                    }
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
                    $handlers,
                    '/books?title=Мечтают ли андроиды об электроовцах?',
                    $loggerFactory,
                    static function () {return AppConfig::createFromArray(include __DIR__ . '/../config/dev/config.php');}
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
            //Arrange и Act
            $appResult = app(...$testItem['in']);

            //Assert
            if ($appResult['httpCode'] === $testItem['out']['httpCode']) {
                echo "    OK --- код ответа\n";
            } else {
                echo "    FAIL - код ответа. Ожидалось: {$testItem['out']['httpCode']}. Актуальное значение: {$appResult['httpCode']}\n";
            }
            $actualResult = json_decode(json_encode($appResult['result']), true);
            $unnecessaryElements = array_diff_assoc_recursive($actualResult, $testItem['out']['result']);
            $missingElements =  array_diff_assoc_recursive($testItem['out']['result'], $actualResult);

            $errMsg = '';

            if (count($unnecessaryElements) > 0) {
                $errMsg .= sprintf("         Есть лишние элементы %s\n", json_encode($unnecessaryElements,JSON_UNESCAPED_UNICODE));
            }
            if (count($missingElements) > 0) {
                $errMsg .= sprintf("         Есть лишние недостающие элементы %s\n", json_encode($missingElements,JSON_UNESCAPED_UNICODE));
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