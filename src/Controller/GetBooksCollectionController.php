<?php

namespace EfTech\BookLibrary\Controller;

use EfTech\BookLibrary\Entity\AbstractTextDocument;
use EfTech\BookLibrary\Infrastructure\Controller\ControllerInterface;
use EfTech\BookLibrary\Infrastructure\http\HttpResponse;
use EfTech\BookLibrary\Infrastructure\http\ServerRequest;
use EfTech\BookLibrary\Infrastructure\http\ServerResponseFactory;
use EfTech\BookLibrary\Infrastructure\Logger\LoggerInterface;
use EfTech\BookLibrary\Infrastructure\Validator\Assert;
use EfTech\BookLibrary\Service\SearchTextDocumentService\SearchTextDocumentService;
use EfTech\BookLibrary\Service\SearchTextDocumentService\SearchTextDocumentServiceCriteria;
use EfTech\BookLibrary\Service\SearchTextDocumentService\TextDocumentDto;
use Exception;
use JsonException;


/**
 * Контроллер поиска книг
 *
 */
class GetBooksCollectionController implements ControllerInterface
{
    /** Логгер
     * @var LoggerInterface
     */
    private LoggerInterface $logger;
    /**
     *
     *
     * @var SearchTextDocumentService
     */
    private SearchTextDocumentService $searchTextDocumentService;

    /**
     * @param LoggerInterface $logger
     * @param SearchTextDocumentService $searchTextDocumentService
     */
    public function __construct(
        LoggerInterface $logger,
        SearchTextDocumentService $searchTextDocumentService

    ) {
        $this->logger = $logger;
        $this->searchTextDocumentService = $searchTextDocumentService;

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
            $params = array_merge($request->getQueryParams(), $request->getAttributes());
            $foundTextDocuments = $this->searchTextDocumentService
                ->search((new SearchTextDocumentServiceCriteria())
                    ->setAuthorSurname($params['author_surname'] ?? null)
                    ->setId($params['id'] ?? null)
                    ->setTitle($params['title'] ?? null)
            );

            $result = $this->buildResult($foundTextDocuments);
            $httpCode = $this->buildHttpCode($foundTextDocuments);


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
     * @param array $foundTextDocuments
     * @return array|AbstractTextDocument
     */
    protected function buildResult(array $foundTextDocuments)
    {
        $result = [];
        foreach ($foundTextDocuments as $foundTextDocument) {
            $result[] = $this->serializeTextDocument($foundTextDocument);
        }
        return $result;

    }
    /**
     *
     * @param TextDocumentDto $textDocument
     *
     * @return array
     */
    final protected function serializeTextDocument(TextDocumentDto $textDocument): array
    {
        $jsonData = [
                'id' => $textDocument->getId(),
                'title' => $textDocument->getTitle(),
                'year' => $textDocument->getYear(),
                'title_for_printing' => $textDocument->getTitleForPrinting()
        ];
        if (TextDocumentDto::TYPE_MAGAZINE === $textDocument->getType()) {
            $jsonData['number'] = $textDocument->getNumber();
        }
        $authorDto = $textDocument->getAuthor();
        if (null !== $authorDto) {
            $jsonData['author'] = [
                'id' => $authorDto->getId(),
                'name' => $authorDto->getName(),
                'surname' => $authorDto->getSurname(),
                'birthday' => $authorDto->getBirthday(),
                'country' => $authorDto->getCountry(),
                ];
        } else {
            $jsonData['author'] = null;
        }
        return $jsonData;
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