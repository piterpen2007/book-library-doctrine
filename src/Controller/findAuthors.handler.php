<?php
namespace EfTech\BookLibrary\Controller;

use EfTech\BookLibrary\Entity\Author;
use EfTech\BookLibrary\Infrastructure\AppConfig;
use EfTech\BookLibrary\Infrastructure\http\httpResponse;
use EfTech\BookLibrary\Infrastructure\http\ServerRequest;
use EfTech\BookLibrary\Infrastructure\http\ServerResponseFactory;
use EfTech\BookLibrary\Infrastructure\Logger\LoggerInterface;
use function EfTech\BookLibrary\Infrastructure\loadData;
use function EfTech\BookLibrary\Infrastructure\paramTypeValidation;

require_once __DIR__ . '/../Infrastructure/app.function.php';


/** Функция поиска авторов
 * @param $request ServerRequest - http запрос
 * @param $appConfig - конфиг приложения
 * @logger LoggerInterface - параметр инкапсулирующий логгирование
 * @return httpResponse - возвращает результат поиска по авторам
 */
return static function (ServerRequest $request, LoggerInterface $logger, AppConfig $appConfig):httpResponse
{
    $authorsJson = loadData($appConfig->getPathToAuthor());
    $logger->log('dispatch "authors" url');

    $paramValidations = [
        'surname' => 'inccorrect surname author'
    ];

    $requestParams = $request->getQueryParams();

    if(null === ($result = paramTypeValidation($paramValidations, $requestParams))) {
        $foundAuthor = [];
        foreach ($authorsJson as $currentAuthor) {
            if (array_key_exists('surname', $requestParams) && $currentAuthor['surname'] === $requestParams['surname']) {
                $foundAuthor[] = Author::createFromArray($currentAuthor);
            }
        }
        $logger->log('found authors: ' . count($foundAuthor));
        $result = [
            'httpCode' => 200,
            'result' => $foundAuthor
        ];
    }
    return ServerResponseFactory::createJsonResponse($result['httpCode'],$result['result']);

};
