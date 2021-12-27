<?php

namespace EfTech\BookLibrary\Controller;

use EfTech\BookLibrary\Entity\AbstractTextDocument;
use EfTech\BookLibrary\Entity\Author;
use EfTech\BookLibrary\Entity\Book;
use EfTech\BookLibrary\Entity\Magazine;
use EfTech\BookLibrary\Infrastructure\Controller\ControllerInterface;
use EfTech\BookLibrary\Infrastructure\DataLoader\JsonDataLoader;
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
class GetBooksCollectionController implements ControllerInterface
{
    /**
     * @var string путь до файла с авторами
     */
    private string $pathToAuthor;
    /**
     * @var string путь до файла с журналами
     */
    private string $pathToMagazines;
    /**
     * @var string путь до файла с книгами
     */
    private string $pathToBooks;
    /** Логгер
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param LoggerInterface $logger
     * @param string $pathToBooks
     * @param string $pathToMagazines
     * @param string $pathToAuthor
     */
    public function __construct(
        LoggerInterface $logger,
        string $pathToBooks,
        string $pathToMagazines,
        string $pathToAuthor
    ) {
        $this->pathToAuthor = $pathToAuthor;
        $this->pathToMagazines = $pathToMagazines;
        $this->pathToBooks = $pathToBooks;
        $this->logger = $logger;
    }


    /** Загрузка данных о текстовых документах
     * @return array
     * @throws JsonException
     */
    private function LoadTextDocumentData():array
    {
        $loader = new JsonDataLoader();
        $books = $loader->loadData($this->pathToBooks);
        $magazines = $loader->loadData($this->pathToMagazines);
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
        $authors = $loader->loadData($this->pathToAuthor);

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
            'id' => 'incorrect book id'
        ];
        $queryParams = array_merge($request->getQueryParams(),$request->getAttributes());
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
        $searchCriteria = array_merge($serverRequest->getQueryParams(),$serverRequest->getAttributes());
        $foundTextDocument = [];
        foreach ($textDocuments as $textDocument) {
            if (array_key_exists("author_surname", $searchCriteria)) {
                $bookMeetSearchCriteria = null !== $textDocument['author_id']
                    && $authorIdToInfo[$textDocument["author_id"]]->getSurname()
                    === $searchCriteria["author_surname"];
            } else {
                $bookMeetSearchCriteria = true;
            }
            if ($bookMeetSearchCriteria && array_key_exists('id',$searchCriteria)) {
                $bookMeetSearchCriteria = $searchCriteria['id'] === (string)$textDocument['id'];
            }
            if ($bookMeetSearchCriteria && array_key_exists("title", $searchCriteria)) {
                $bookMeetSearchCriteria = $searchCriteria["title"] === $textDocument["title"];
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
            $textDocuments = $this->LoadTextDocumentData();
            $authorIdToEntity = $this->loadAuthorEntity();

            $foundTextDocument = $this->searchTextDocument($textDocuments, $authorIdToEntity , $request);

            $result = $this->buildResult($foundTextDocument);
            $httpCode = $this->buildHttpCode($foundTextDocument);


        } else {
            $httpCode = 500;
            $result=[
                'status' => 'fail',
                'message' => $resultOfParamValidation
            ];
        }
        return ServerResponseFactory::createJsonResponse($httpCode,$result);

    }

    /** Подготавливает данные для ответа
     * @param array $foundTextDocument
     * @return array|AbstractTextDocument
     */
    protected function buildResult(array $foundTextDocument)
    {
        return $foundTextDocument;
    }

    /** Подготавливает http code
     * @param array $foundTextDocument
     * @return int
     */
    protected function buildHttpCode(array $foundTextDocument):int
    {
        return 200;
    }


}