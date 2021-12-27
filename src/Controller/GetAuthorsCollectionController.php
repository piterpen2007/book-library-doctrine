<?php

namespace EfTech\BookLibrary\Controller;


use EfTech\BookLibrary\Entity\Author;
use EfTech\BookLibrary\Infrastructure\Controller\ControllerInterface;
use EfTech\BookLibrary\Infrastructure\DataLoader\JsonDataLoader;
use EfTech\BookLibrary\Infrastructure\http\HttpResponse;
use EfTech\BookLibrary\Infrastructure\http\ServerRequest;
use EfTech\BookLibrary\Infrastructure\http\ServerResponseFactory;
use EfTech\BookLibrary\Infrastructure\Logger\LoggerInterface;
use EfTech\BookLibrary\Infrastructure\Validator\Assert;
use Exception;
use JsonException;


/** Контроллер поиска авторов
 *
 */
class GetAuthorsCollectionController implements ControllerInterface
{
    private string $pathToAuthor;
    /** Логгер
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param string $pathToAuthor
     * @param LoggerInterface $logger
     */
    public function __construct(string $pathToAuthor, LoggerInterface $logger)
    {

        $this->pathToAuthor = $pathToAuthor;
        $this->logger = $logger;
    }


    /** Загружает данные о авторах
     * @return array
     * @throws JsonException
     */
    private function loadData():array
    {
        return (new JsonDataLoader())->loadData($this->pathToAuthor);
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
        $searchCriteria = array_merge($serverRequest->getQueryParams(),$serverRequest->getAttributes());
        foreach ($authors as $currentAuthor) {
            if (array_key_exists("surname", $searchCriteria)) {
                $authorMeetSearchCriteria = $searchCriteria['surname'] === $currentAuthor['surname'];
            } else {
                $authorMeetSearchCriteria = true;
            }
            if ($authorMeetSearchCriteria && array_key_exists('id', $searchCriteria)) {
                $authorMeetSearchCriteria = $searchCriteria['id'] === (string)$currentAuthor['id'];
            }

            if ($authorMeetSearchCriteria) {
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
            'id' => 'incorrect author id'
        ];

        $params = array_merge($serverRequest->getQueryParams(),$serverRequest->getAttributes());

        return Assert::arrayElementsIsString($paramsValidation,$params);
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
            $authors = $this->loadData();
            $foundAuthors = $this->searchForAuthorsInData($authors,$request);
            $httpCode = $this->buildHttpCode($foundAuthors);
            $result = $this->buildResult($foundAuthors);
        } else {
            $httpCode = 500;
            $result=[
                'status' => 'fail',
                'message' => $resultOfParamValidation
            ];
        }
        return ServerResponseFactory::createJsonResponse($httpCode,$result);

    }

    /** Определяет http code
     * @param array $foundAuthors
     * @return int
     */
    protected function buildHttpCode(array $foundAuthors):int
    {
        return 200;
    }

    /** Подготавливает данные для ответа
     * @param array $foundAuthors
     * @return array|Author
     */
    protected function buildResult(array $foundAuthors)
    {
        return $foundAuthors;
    }

}