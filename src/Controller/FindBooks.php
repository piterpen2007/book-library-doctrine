<?php

namespace EfTech\BookLibrary\Controller;

use EfTech\BookLibrary\Entity\AbstractTextDocument;
use EfTech\BookLibrary\Entity\Author;
use EfTech\BookLibrary\Entity\Book;
use EfTech\BookLibrary\Entity\Magazine;
use EfTech\BookLibrary\Infrastructure\AppConfig;
use EfTech\BookLibrary\Infrastructure\Controller\ControllerInterface;
use EfTech\BookLibrary\Infrastructure\DataLoader\JsonDataLoader;
use EfTech\BookLibrary\Infrastructure\DI\ServiceLocator;
use EfTech\BookLibrary\Infrastructure\http\HttpResponse;
use EfTech\BookLibrary\Infrastructure\http\ServerRequest;
use EfTech\BookLibrary\Infrastructure\http\ServerResponseFactory;
use EfTech\BookLibrary\Infrastructure\Logger\LoggerInterface;
use EfTech\BookLibrary\Infrastructure\Validator\Assert;
use Exception;
use JsonException;


/**
 * Контроллер поиска книг
 *
 */
class FindBooks implements ControllerInterface
{
    /** Логгер
     * @var LoggerInterface
     */
    private LoggerInterface $logger;
    /** Конфиг приложения
     * @var AppConfig
     */
    private AppConfig $appConfig;

    /**
     * @param ServiceLocator $sl
     */
    public function __construct(ServiceLocator $sl)
    {
        $this->logger = $sl->get(LoggerInterface::class);
        $this->appConfig = $sl->get(AppConfig::class);
    }

    /** Загрузка данных о текстовых документах
     * @return array
     * @throws JsonException
     */
    private function LoadTextDocumentData():array
    {
        $loader = new JsonDataLoader();
        $books = $loader->loadData($this->appConfig->getPathToBooks());
        $magazines = $loader->loadData($this->appConfig->getPathToMagazines());
        return array_merge($books, $magazines);
    }

    /** Загружает данные о авторе и делает хеш мапу
     * @return array
     * @throws JsonException
     * @throws Exception
     */
    private function loadAuthorEntity():array
    {
        $loader = new JsonDataLoader();
        $authors = $loader->loadData($this->appConfig->getPathToAuthor());

        $authorIdToInfo = [];

        foreach ($authors as $author) {
            $authorObj = Author::createFromArray($author);

            $authorIdToInfo[$authorObj->getId()] = $authorObj;
        }
        return $authorIdToInfo;
    }

    /**  Валдирует параматры запроса
     * @param ServerRequest $request
     * @return string|null
     */
    private function validateQueryParams(ServerRequest $request):?string
    {
        $paramTypeValidation = [
            'author_surname' => "incorrect author surname",
            'title' => 'incorrect book title',
        ];
        $queryParams = $request->getQueryParams();
        return Assert::arrayElementsIsString($paramTypeValidation,$queryParams);
    }

    /** Реализация логики создания текстового документа
     * @param array $textDocument - данные о текствовом документе
     * @param Author[] $authorIdToInfo - коллекция сущностей автор, ключом является id  автора
     * @return AbstractTextDocument
     */
    private function textDocumentFactory(array $textDocument,array $authorIdToInfo):AbstractTextDocument
    {
        $textDocument['author'] = null === $textDocument['author_id'] ? null : $authorIdToInfo[$textDocument['author_id']];

        if (array_key_exists('number', $textDocument)) {
            $textDocumentObj = Magazine::createFromArray($textDocument);
        } else {
            $textDocumentObj = Book::createFromArray($textDocument);
        }
        return $textDocumentObj;
    }
    private function searchTextDocument(
        array $textDocuments,
        array $authorIdToInfo,
        ServerRequest $serverRequest
    ):array
    {
        $requestParams = $serverRequest->getQueryParams();
        $foundTextDocument = [];
        foreach ($textDocuments as $textDocument) {
            if (array_key_exists("author_surname", $requestParams)) {
                $bookMeetSearchCriteria = null !== $textDocument['author_id']
                    && $authorIdToInfo[$textDocument["author_id"]]->getSurname()
                    === $requestParams["author_surname"];
            } else {
                $bookMeetSearchCriteria = true;
            }
            if ($bookMeetSearchCriteria && array_key_exists("title", $requestParams)) {
                $bookMeetSearchCriteria = $requestParams["title"] === $textDocument["title"];
            }

            if ($bookMeetSearchCriteria) {
                $foundTextDocument[] = $this->textDocumentFactory($textDocument,$authorIdToInfo);
            }
        }
        $this->logger->log("Найдено книг: " . count($foundTextDocument));


        return $foundTextDocument;
    }

    /** Реализация поиска книг по критериям
     * @param ServerRequest $request - серверный объект запроса
     * @return HttpResponse - объект http ответа
     * @throws JsonException
     * @throws Exception
     */
    public function __invoke(ServerRequest $request): HttpResponse
    {
        $this->logger->log("Ветка books");
        $resultOfParamValidation = $this->validateQueryParams($request);

        if (null === $resultOfParamValidation) {
            $httpCode = 200;
            $textDocuments = $this->LoadTextDocumentData();
            $authorIdToEntity = $this->loadAuthorEntity();
            $result = $this->searchTextDocument($textDocuments, $authorIdToEntity , $request);
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