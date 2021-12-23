<?php

namespace EfTech\BookLibrary\Controller;


use EfTech\BookLibrary\Entity\Author;
use EfTech\BookLibrary\Infrastructure\App;
use EfTech\BookLibrary\Infrastructure\AppConfig;
use EfTech\BookLibrary\Infrastructure\Controller\ControllerInterface;
use EfTech\BookLibrary\Infrastructure\DataLoader\JsonDataLoader;
use EfTech\BookLibrary\Infrastructure\http\HttpResponse;
use EfTech\BookLibrary\Infrastructure\http\ServerRequest;
use EfTech\BookLibrary\Infrastructure\http\ServerResponseFactory;
use EfTech\BookLibrary\Infrastructure\Logger\FileLogger\Logger;
use EfTech\BookLibrary\Infrastructure\Logger\LoggerInterface;
use EfTech\BookLibrary\Infrastructure\Validator\Assert;
use Exception;
use JsonException;


/** Контроллер поиска авторов
 *
 */
final class FindAuthors implements ControllerInterface
{
    /** Логгер
     * @var LoggerInterface
     */
    private LoggerInterface $logger;
    /** Конфиг приложения
     * @var AppConfig
     */
    private ?AppConfig $appConfig;

    /**
     * @param AppConfig|null $appConfig
     * @param LoggerInterface $logger
     */
    public function __construct(?AppConfig $appConfig, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->appConfig = $appConfig;
    }


    /** Загружает данные о авторах
     * @return array
     * @throws JsonException
     */
    private function loadData():array
    {
        $loader = new JsonDataLoader();
        return $loader->loadData($this->appConfig->getPathToAuthor());
    }

    /** Алгоритм поиска авторов
     * @param array $authors
     * @param ServerRequest $serverRequest
     * @return array
     * @throws Exception
     */
    private function searchForAuthorsInData(array $authors,ServerRequest $serverRequest):array
    {
        $findAuthors = [];
        $requestParams =$serverRequest->getQueryParams();
        foreach ($authors as $currentAuthor) {
            if (array_key_exists("surname", $requestParams) && $requestParams['surname'] === $currentAuthor['surname']) {
                $findAuthors[] = Author::createFromArray($currentAuthor);
            }
        }
        $this->logger->log("Найдено авторов : " . count($findAuthors));
        return $findAuthors;
    }

    /** Валидирует параметры запроса
     * @param ServerRequest $serverRequest - объект серверного http запроса
     * @return string|null - строка с ошибкой или нулл если ошибки нет
     */
    private function validateQueryParams(ServerRequest $serverRequest): ?string
    {
        $paramsValidation = [
            'surname' => 'incorrect author surname',
        ];
        $queryParams = $serverRequest->getQueryParams();

        return Assert::arrayElementsIsString($paramsValidation,$queryParams);
    }

    /**
     * @param ServerRequest $request - серверный объект запроса
     * @return HttpResponse - объект http ответа
     * @throws JsonException
     * @throws Exception
     */
    public function __invoke(ServerRequest $request): HttpResponse
    {
        $this->logger->log("Ветка authors");

        $resultOfParamValidation = $this->validateQueryParams($request);

        if (null === $resultOfParamValidation) {
            $httpCode = 200;
            $authors = $this->loadData();
            $result = $this->searchForAuthorsInData($authors,$request);
        } else {
            $httpCode = 500;

            $result=[
                'status' => 'fail',
                'message' => $resultOfParamValidation
            ];
        }
        return ServerResponseFactory::createJsonResponse($httpCode,$result);

    }

}