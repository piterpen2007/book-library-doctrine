<?php

namespace EfTech\BookLibrary\Controller;

use EfTech\BookLibrary\Infrastructure\Controller\ControllerInterface;
use EfTech\BookLibrary\Infrastructure\http\ServerResponseFactory;
use EfTech\BookLibrary\Service\ArrivalNewTextDocumentService;
use EfTech\BookLibrary\Service\ArrivalNewTextDocumentService\NewBookDto;
use EfTech\BookLibrary\Service\ArrivalNewTextDocumentService\ResultRegisteringTextDocumentDto;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 *  Контроллер реализующий логику регистрации новых журналов
 */
class CreateRegisterBooksController implements ControllerInterface
{
    private ArrivalNewTextDocumentService $arrivalNewTextDocumentService;

    /**
     * @param ArrivalNewTextDocumentService $arrivalNewTextDocumentService
     */
    public function __construct(ArrivalNewTextDocumentService $arrivalNewTextDocumentService)
    {
        $this->arrivalNewTextDocumentService = $arrivalNewTextDocumentService;
    }


    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $requestData = json_decode($request->getBody(), true, 512, JSON_THROW_ON_ERROR);
            $validationResult = $this->validateData($requestData);

            if (0 === count($validationResult)) {
                // Создаю dto с входными данными
                $responseDto = $this->runService($requestData);
                $httpCode = 201;
                $jsonData = $this->buildJsonData($responseDto);
            } else {
                $httpCode = 400;
                $jsonData = ['status' => 'fail','message' => implode('.', $validationResult)];
            }
        } catch (\Throwable $e) {
            $httpCode = 500;
            $jsonData = ['status' => 'fail','message' => $e->getMessage()];
        }

        return ServerResponseFactory::createJsonResponse($httpCode, $jsonData);
    }

    private function runService(array $requestData): ResultRegisteringTextDocumentDto
    {
        $requestDto = new NewBookDto(
            $requestData['title'],
            $requestData['year'],
            $requestData['author_id'],
        );

        return $this->arrivalNewTextDocumentService->registerBook($requestDto);
    }

    /** Формирует результаты для ответа на основе dto
     * @param ResultRegisteringTextDocumentDto $responseDto
     * @return array
     */
    private function buildJsonData(ResultRegisteringTextDocumentDto $responseDto): array
    {
        return [
          'id' => $responseDto->getId(),
          'title_for_printing' => $responseDto->getTitleForPrinting(),
          'status' => $responseDto->getStatus()
        ];
    }

    /** Валидирует входные данные
     * @param $requestData
     * @return array
     */
    private function validateData($requestData): array
    {
        $err = [];
        if (false === is_array($requestData)) {
            $err[] = 'Данные о новой книге не являются массивом';
        } else {
            if (false === array_key_exists('title', $requestData)) {
                $err[] = 'Отсутствует информация о заголовке книги';
            } elseif (false === is_string($requestData['title'])) {
                $err[] = 'Заголовок книги должен быть строкой';
            } elseif ('' === trim($requestData['title'])) {
                $err[] = 'Заголовок книги не может быть пустой строкой';
            }

            if (false === array_key_exists('year', $requestData)) {
                $err[] = 'Отсутствует информация о годе издания книги';
            } elseif (false === is_int($requestData['year'])) {
                $err[] = 'Год издания книги должен быть целым числом';
            } elseif ($requestData['year'] <= 0) {
                $err[] = 'Год издания книги не может быть меньше или равен нуля';
            }

            if (false === array_key_exists('author_id', $requestData)) {
                $err[] = 'Отсутствует информация о id автора книги';
            } elseif (false === is_int($requestData['author_id'])) {
                $err[] = 'Id автора должно быть целым числом';
            }
        }

        return $err;
    }
}
