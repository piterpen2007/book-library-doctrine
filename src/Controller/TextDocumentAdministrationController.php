<?php

namespace EfTech\BookLibrary\Controller;

use EfTech\BookLibrary\Exception\RuntimeException;
use EfTech\BookLibrary\Infrastructure\Auth\HttpAuthProvider;
use EfTech\BookLibrary\Infrastructure\Controller\ControllerInterface;
use EfTech\BookLibrary\Infrastructure\http\ServerResponseFactory;
use Psr\Log\LoggerInterface;
use EfTech\BookLibrary\Infrastructure\ViewTemplate\ViewTemplateInterface;
use EfTech\BookLibrary\Service\ArrivalNewTextDocumentService;
use EfTech\BookLibrary\Service\ArrivalNewTextDocumentService\NewBookDto;
use EfTech\BookLibrary\Service\ArrivalNewTextDocumentService\NewMagazineDto;
use EfTech\BookLibrary\Service\SearchAuthorsService;
use EfTech\BookLibrary\Service\SearchAuthorsService\SearchAuthorsCriteria;
use EfTech\BookLibrary\Service\SearchTextDocumentService;
use EfTech\BookLibrary\Service\SearchTextDocumentService\SearchTextDocumentServiceCriteria;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class TextDocumentAdministrationController implements ControllerInterface
{
    private ServerResponseFactory $serverResponseFactory;
    private HttpAuthProvider $httpAuthProvider;
    /** сервис добавления текстового документа
     * @var ArrivalNewTextDocumentService
     */
    private ArrivalNewTextDocumentService $arrivalNewTextDocumentService;
    /** Сервис поиска авторов
     * @var SearchAuthorsService
     */
    private SearchAuthorsService $authorsService;
    /** шаблонизатор для рендеринга html
     * @var ViewTemplateInterface
     */
    private ViewTemplateInterface $viewTemplate;
    /** Логер
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /** Сервис поиска текстового документа
     * @var SearchTextDocumentService
     */
    private SearchTextDocumentService $searchTextDocumentService;

    /**
     * @param LoggerInterface $logger Логер
     * @param SearchTextDocumentService $searchTextDocumentService Сервис поиска текстового документа
     * @param ViewTemplateInterface $viewTemplate
     * @param SearchAuthorsService $authorsService
     * @param ArrivalNewTextDocumentService $arrivalNewTextDocumentService
     * @param HttpAuthProvider $httpAuthProvider
     * @param ServerResponseFactory $serverResponseFactory
     */
    public function __construct(
        LoggerInterface $logger,
        SearchTextDocumentService $searchTextDocumentService,
        ViewTemplateInterface $viewTemplate,
        SearchAuthorsService $authorsService,
        ArrivalNewTextDocumentService $arrivalNewTextDocumentService,
        HttpAuthProvider $httpAuthProvider,
        \EfTech\BookLibrary\Infrastructure\http\ServerResponseFactory $serverResponseFactory
    ) {
        $this->logger = $logger;
        $this->searchTextDocumentService = $searchTextDocumentService;
        $this->viewTemplate = $viewTemplate;
        $this->authorsService = $authorsService;
        $this->arrivalNewTextDocumentService = $arrivalNewTextDocumentService;
        $this->httpAuthProvider = $httpAuthProvider;
        $this->serverResponseFactory = $serverResponseFactory;
    }


    /**
     * @inheritDoc
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        try {
            if (false === $this->httpAuthProvider->isAuth()) {
                //todo
                return $this->httpAuthProvider->doAuth($request->getUri());
            }
            $this->logger->info('run TextDocumentAdministrationController::__invoke');
            $resultCreationTextDocument = [];
            if ('POST' === $request->getMethod()) {
                $resultCreationTextDocument = $this->creationOfTextDocument($request);
            }
            $dtoBooksCollection = $this->searchTextDocumentService->search(new SearchTextDocumentServiceCriteria());
            $dtoAuthorsCollection = $this->authorsService->search(new SearchAuthorsCriteria());
            $viewData = [
                'textDocuments' => $dtoBooksCollection,
                'authors' => $dtoAuthorsCollection
            ];
            $contex = array_merge($viewData, $resultCreationTextDocument);
            $template = 'textDocument.administration.twig';
            $httpCode = 200;
        } catch (Throwable $e) {
            $httpCode = 500;
            $template = 'errors.twig';
            $contex = [
                'errors' => [
                    $e->getMessage()
                ]
            ];
        }
        $html = $this->viewTemplate->render(
            $template,
            $contex
        );

        return $this->serverResponseFactory->createHtmlResponse($httpCode, $html);
    }

    /** Результат создания текстовых документов
     *
     * @param ServerRequestInterface $request
     * @return array - данные о ошибках у форм создания книг и журналов
     */
    private function creationOfTextDocument(ServerRequestInterface $request): array
    {
        $dataToCreate = [];
        parse_str($request->getBody(), $dataToCreate);
        if (false === array_key_exists('type', $dataToCreate)) {
            throw new RuntimeException('Отсутствуют данные о типе текстового документа');
        }

        $result = [
            'formValidationResults' => [
                'book' => [],
                'magazine' => [],
            ]
        ];

        if ('book' === $dataToCreate['type']) {
            $result['formValidationResults']['book'] = $this->validateBook($dataToCreate);

            if (0 === count($result['formValidationResults']['book'])) {
                $this->createBook($dataToCreate);
            } else {
                $result['bookData'] = $dataToCreate;
            }
        } elseif ('magazine' === $dataToCreate['type']) {
            $result['formValidationResults']['magazine'] = $this->validateMagazine($dataToCreate);

            if (0 === count($result['formValidationResults']['magazine'])) {
                $this->createMagazine($dataToCreate);
            } else {
                $result['magazineData'] = $dataToCreate;
            }
        } else {
            throw new RuntimeException('Неизвестный тип текстового документа');
        }


        return $result;
    }

    /** Логика валидации данных книги
     * @param array $dataToCreate
     * @return void
     */
    private function validateBook(array $dataToCreate): array
    {
        $errs = [];
        $errTitle = $this->validateTitle($dataToCreate);

        if (count($errTitle) > 0) {
            $errs = array_merge($errs, $errTitle);
        }

        $errYear = $this->validateYear($dataToCreate);
        if (count($errYear) > 0) {
            $errs = array_merge($errs, $errYear);
        }
        $this->validateBookAuthor($dataToCreate);


        return $errs;
    }

    /** Создаёт книгу
     * @param array $dataToCreate
     * @return void
     */
    private function createBook(array $dataToCreate)
    {
        $this->arrivalNewTextDocumentService->registerBook(
            new NewBookDto(
                $dataToCreate['title'],
                (int)$dataToCreate['year'],
                (int)$dataToCreate['author_id']
            )
        );
    }

    /** Логика валидации данных магазина
     * @param array $dataToCreate
     * @return void
     */
    private function validateMagazine(array $dataToCreate): array
    {
        $errs = [];
        $errTitle = $this->validateTitle($dataToCreate);

        if (count($errTitle) > 0) {
            $errs = array_merge($errs, $errTitle);
        }

        $errYear = $this->validateYear($dataToCreate);
        if (count($errYear) > 0) {
            $errs = array_merge($errs, $errYear);
        }
        $this->validateMagazineBookAuthor($dataToCreate);

        $errNumber = $this->validateNumber($dataToCreate);
        if (count($errNumber) > 0) {
            $errs = array_merge($errs, $errNumber);
        }


        return $errs;
    }
    private function validateBookAuthor(array $dataToCreate): void
    {
        if (false === array_key_exists('author_id', $dataToCreate)) {
            throw new RuntimeException('Нет данных о авторе');
        } elseif (false === is_string($dataToCreate['author_id'])) {
            throw new RuntimeException('Данные о авторе должны быть строкой');
        }
    }

    private function validateYear(array $dataToCreate): array
    {
        $errs = [];
        if (false === array_key_exists('year', $dataToCreate)) {
            throw new RuntimeException('Нет данных о годе');
        } elseif (false === is_string($dataToCreate['year'])) {
            throw new RuntimeException('Данные о годе должны быть строкой');
        } else {
            $trimYear = trim($dataToCreate['year']);
            $yearIsValid = 1 === preg_match('/^[0-9]{4}$/', $trimYear);

            $errYear = [];
            if (false === $yearIsValid) {
                $errYear[] = 'Год должен быть числом из 4 цифр';
            } elseif ((int)$trimYear === 0) {
                $errYear[] = 'Год должен быть больше 0';
            } elseif ((int)$trimYear > (int)date('Y')) {
                $errYear[] = 'Год не может быть ' . date('Y');
            }
            if (0 !== count($errYear)) {
                $errs['year'] = $errYear;
            }
        }
        return $errs;
    }
    /** Валидация заголовка
     * @param array $dataToCreate
     * @return array
     */
    private function validateTitle(array $dataToCreate): array
    {
        $errs = [];

        if (false === array_key_exists('title', $dataToCreate)) {
            throw new RuntimeException('Нет данных о заголовке');
        } elseif (false === is_string($dataToCreate['title'])) {
            throw new RuntimeException('Данные о заголовке должны быть строкой');
        } else {
            $titleLength = strlen(trim($dataToCreate['title']));
            $errTitle = [];
            if ($titleLength > 250) {
                $errTitle[] = 'заголовок не может быть длинее 250 символов';
            } elseif (0 === $titleLength) {
                $errTitle[] = 'заголовок не может быть пустым';
            }

            if (0 !== count($errTitle)) {
                $errs['title'] = $errTitle;
            }
        }
        return $errs;
    }

    /** Создаёт магазин
     * @param array $dataToCreate
     * @return void
     */
    private function createMagazine(array $dataToCreate)
    {
        $this->arrivalNewTextDocumentService->registerMagazine(
            new NewMagazineDto(
                $dataToCreate['title'],
                (int)$dataToCreate['year'],
                'null' === $dataToCreate['author_id'] ? null : (int)$dataToCreate['author_id'],
                (int)$dataToCreate['number']
            )
        );
    }

    private function validateNumber(array $dataToCreate): array
    {
        $errs = [];
        if (false === array_key_exists('number', $dataToCreate)) {
            throw new RuntimeException('Нет данных о номере журнала');
        } elseif (false === is_string($dataToCreate['number'])) {
            throw new RuntimeException('Данные о номере журнала должны быть строкой');
        } else {
            $trimNumber = trim($dataToCreate['number']);
            $numberIsValid = 1 === preg_match('/^\d+$/', $trimNumber);

            $errsNumber = [];
            if (false === $numberIsValid) {
                $errsNumber[] = 'Номер должен быть числом ';
            }
            if (0 !== count($errsNumber)) {
                $errs['number'] = $errsNumber;
            }
        }
        return $errs;
    }

    private function validateMagazineBookAuthor(array $dataToCreate): void
    {
        if (false === array_key_exists('author_id', $dataToCreate)) {
            throw new RuntimeException('Нет данных о авторе');
        } elseif (false === is_string($dataToCreate['author_id'])) {
            throw new RuntimeException('Данные о авторе должны быть строкой');
        }
        if (!('null' === $dataToCreate['author_id'] || 1 === preg_match('/^\d+$/', $dataToCreate['author_id']))) {
            throw new RuntimeException('Данные о авторе имеют не корректный формат');
        }
    }
}
