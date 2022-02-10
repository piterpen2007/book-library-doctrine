<?php

namespace EfTech\BookLibrary\Controller;

use EfTech\BookLibrary\Infrastructure\Controller\ControllerInterface;
use EfTech\BookLibrary\Infrastructure\http\ServerResponseFactory;
use EfTech\BookLibrary\Infrastructure\Logger\LoggerInterface;
use EfTech\BookLibrary\Infrastructure\Validator\Assert;
use EfTech\BookLibrary\Service\SearchTextDocumentService;
use EfTech\BookLibrary\Service\SearchTextDocumentService\SearchTextDocumentServiceCriteria;
use EfTech\BookLibrary\Service\SearchTextDocumentService\TextDocumentDto;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Контроллер поиска книг
 *
 */
class GetBooksCollectionController implements ControllerInterface
{
    private ServerResponseFactory $serverResponseFactory;
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
     * @param ServerResponseFactory $serverResponseFactory
     */
    public function __construct(
        LoggerInterface $logger,
        SearchTextDocumentService $searchTextDocumentService,
        \EfTech\BookLibrary\Infrastructure\http\ServerResponseFactory $serverResponseFactory
    ) {
        $this->logger = $logger;
        $this->searchTextDocumentService = $searchTextDocumentService;
        $this->serverResponseFactory = $serverResponseFactory;
    }


    /**  Валдирует параматры запроса
     * @param ServerRequestInterface $request
     * @return string|null
     */
    private function validateQueryParams(ServerRequestInterface $request): ?string
    {
        $paramTypeValidation = [
            'author_surname' => "incorrect author surname",
            'title' => 'incorrect book title',
            'id' => 'incorrect book id'
        ];
        $queryParams = array_merge($request->getQueryParams(), $request->getAttributes());
        return Assert::arrayElementsIsString($paramTypeValidation, $queryParams);
    }


    /** Реализация поиска книг по критериям
     * @param ServerRequestInterface $request - серверный объект запроса
     * @return ResponseInterface - объект http ответа
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $this->logger->info("Ветка books");
        $resultOfParamValidation = $this->validateQueryParams($request);

        if (null === $resultOfParamValidation) {
            $params = array_merge($request->getQueryParams(), $request->getAttributes());
            $foundTextDocuments = $this->searchTextDocumentService
                ->search((new SearchTextDocumentServiceCriteria())
                    ->setAuthorSurname($params['author_surname'] ?? null)
                    ->setId($params['id'] ?? null)
                    ->setTitle($params['title'] ?? null));

            $result = $this->buildResult($foundTextDocuments);
            $httpCode = $this->buildHttpCode($foundTextDocuments);
        } else {
            $httpCode = 500;
            $result = [
                'status' => 'fail',
                'message' => $resultOfParamValidation
            ];
        }
        return $this->serverResponseFactory->createJsonResponse($httpCode, $result);
    }

    /** Подготавливает данные для ответа
     * @param array $foundTextDocuments
     * @return array
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
    protected function buildHttpCode(array $foundTextDocument): int
    {
        return 200;
    }
}
