<?php

namespace EfTech\BookLibraryTest\Infrastructure\Controller;

require_once __DIR__ . '/../../src/Infrastructure/Autoloader.php';

use EfTech\BookLibrary\Controller\GetAuthorsCollectionController;
use EfTech\BookLibrary\Entity\AuthorRepositoryInterface;
use EfTech\BookLibrary\Infrastructure\AppConfig;
use EfTech\BookLibrary\Infrastructure\Autoloader;
use EfTech\BookLibrary\Infrastructure\DI\Container;
use EfTech\BookLibrary\Infrastructure\http\ServerRequest;
use EfTech\BookLibrary\Infrastructure\Logger\Adapter\NullAdapter;
use EfTech\BookLibrary\Infrastructure\Logger\Logger;
use EfTech\BookLibrary\Infrastructure\Logger\LoggerInterface;
use EfTech\BookLibrary\Infrastructure\Uri\Uri;
use EfTech\BookLibrary\Repository\AuthorJsonFileRepository;
use EfTech\BookLibrary\Service\SearchAuthorsService;
use EfTech\BookLibraryTest\TestUtils;


spl_autoload_register(
    new Autoloader([
        'EfTech\\BookLibrary\\' => __DIR__ . '/../../src/',
        'EfTech\\BookLibraryTest\\' => __DIR__ . '/../../test/'
    ])
);

/**
 * Тестирование контроллера FindAuthors
 */
class FindAuthorsTest
{
    /** Тестирование поиска авторов по фамилии
     * @return void
     * @throws \JsonException
     */
    public static function testSearchAuthorsBySurname():void
    {
        echo "-------------------Тестирование поиска автора по фамилии-----------------------\n";
        $httpRequest = new ServerRequest(
            'GET',
            '1.1',
            '/authors?surname=Паланик',
            Uri::createFromString('http://book-library-fedyancev.ru:8083/authors?surname=Паланик'),
            ['Content-Type'=> 'application/json'],
            null
        );
        $appConfig = AppConfig::createFromArray(require __DIR__ . '/../../config/dev/config.php');
        $diContainer = new Container(
            [
                LoggerInterface::class => new Logger(new NullAdapter()),
                'pathToAuthors' => $appConfig->getPathToAuthor()
            ],
            [
                GetAuthorsCollectionController::class => [
                    'args' => [
                        'logger' => LoggerInterface::class,
                        'searchAuthorsService' => SearchAuthorsService::class,
                    ]
                ],
                SearchAuthorsService::class => [
                    'args' => [
                        'logger' => \EfTech\BookLibrary\Infrastructure\Logger\LoggerInterface::class,
                        'authorRepository' => AuthorRepositoryInterface::class
                    ]
                ],
                \EfTech\BookLibrary\Infrastructure\DataLoader\DataLoaderInterface::class => [
                    'class' => \EfTech\BookLibrary\Infrastructure\DataLoader\JsonDataLoader::class
                ],
                AuthorRepositoryInterface::class => [
                    'class' => AuthorJsonFileRepository::class,
                    'args' => [
                        'pathToAuthors' => 'pathToAuthors',
                        'dataLoader' => \EfTech\BookLibrary\Infrastructure\DataLoader\DataLoaderInterface::class
                    ]

                ],
                ]
        );

        $findAuthors = $diContainer->get(GetAuthorsCollectionController::class);
        $httpResponse = $findAuthors($httpRequest);
        //Assert
        if ($httpResponse->getStatusCode() === 200) {
            echo "    OK --- код ответа\n";
        } else {
            echo "    FAIL - код ответа. Ожидалось: 200. Актуальное значение: {$httpResponse->getStatusCode()}\n";
        }
        $expected = [
            [
                'id'=> 1,
                'name'=> 'Чак',
                'surname'=>'Паланик',
                'birthday'=> '21.02.1962',
                'country' => 'us'
            ]
        ];

        $actualResult =  json_decode($httpResponse->getBody(), true, 512 , JSON_THROW_ON_ERROR);

        $unnecessaryElements = TestUtils::arrayDiffAssocRecursive($actualResult, $expected);
        $missingElements =  TestUtils::arrayDiffAssocRecursive($expected, $actualResult);

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
FindAuthorsTest::testSearchAuthorsBySurname();