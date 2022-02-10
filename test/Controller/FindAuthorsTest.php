<?php

namespace EfTech\BookLibraryTest\Controller;

use EfTech\BookLibrary\Config\AppConfig;
use EfTech\BookLibrary\Controller\GetAuthorsCollectionController;
use EfTech\BookLibrary\Infrastructure\DataLoader\JsonDataLoader;
use EfTech\BookLibrary\Infrastructure\http\ServerRequest;
use EfTech\BookLibrary\Infrastructure\Logger\Adapter\NullAdapter;
use EfTech\BookLibrary\Infrastructure\Logger\Logger;
use EfTech\BookLibrary\Repository\AuthorJsonFileRepository;
use EfTech\BookLibrary\Service\SearchAuthorsService;
use JsonException;
use Nyholm\Psr7\Uri;
use PHPUnit\Framework\TestCase;

/**
 * Тестирование контроллера FindAuthors
 */
class FindAuthorsTest extends TestCase
{
    /** Тестирование поиска авторов по фамилии
     *
     * @throws JsonException
     */
    public function testSearchAuthorsBySurname(): void
    {
        //Arrange
        $httpRequest = new ServerRequest(
            'GET',
            '1.1',
            '/authors?surname=Паланик',
            new Uri('http://book-library-fedyancev.ru:8083/authors?surname=Паланик'),
            ['Content-Type' => 'application/json'],
            null
        );
        $appConfig = AppConfig::createFromArray(require __DIR__ . '/../../config/dev/config.php');
        $logger = new Logger(new NullAdapter());

        $controller = new GetAuthorsCollectionController(
            $logger,
            new SearchAuthorsService(
                $logger,
                new AuthorJsonFileRepository(
                    $appConfig->getPathToAuthor(),
                    new JsonDataLoader()
                )
            )
        );

        //Act
        $httpResponse = $controller($httpRequest);
        $actualResult =  json_decode($httpResponse->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $expected = [
            [
                'id' => 1,
                'name' => 'Чак',
                'surname' => 'Паланик',
                'birthday' => '21.02.1962',
                'country' => 'us'
            ]
        ];

        //Assert
        $this->assertEquals(200, $httpResponse->getStatusCode(), 'код http ответа не корректен');
        $this->assertEquals($expected, $actualResult, 'Данные ответа не валидны');
    }
}
