<?php

namespace EfTech\BookLibrary\Controller;

use EfTech\BookLibrary\Infrastructure\Controller\ControllerInterface;
use EfTech\BookLibrary\Infrastructure\http\ServerResponseFactory;
use EfTech\BookLibrary\Service\ArrivalNewTextDocumentService;
use EfTech\BookLibrary\Service\ArrivalNewTextDocumentService\NewMagazineDto;
use EfTech\BookLibrary\Service\ArrivalNewTextDocumentService\ResultRegisteringTextDocumentDto;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 *  Контроллер реализующий логику обработки запроса добавления жрунала
 */
class CreateRegisterMagazinesController implements ControllerInterface
{
    private ServerResponseFactory $serverResponseFactory;
    private ArrivalNewTextDocumentService $arrivalNewTextDocumentService;

    /**
     * @param ArrivalNewTextDocumentService $arrivalNewTextDocumentService
     * @param ServerResponseFactory $serverResponseFactory
     */
    public function __construct(
        ArrivalNewTextDocumentService $arrivalNewTextDocumentService,
        \EfTech\BookLibrary\Infrastructure\http\ServerResponseFactory $serverResponseFactory
    )
    {
        $this->arrivalNewTextDocumentService = $arrivalNewTextDocumentService;
        $this->serverResponseFactory = $serverResponseFactory;
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

        return $this->serverResponseFactory->createJsonResponse($httpCode, $jsonData);
    }

    /** Запуск сервиса
     * @param array $requestData
     * @return ResultRegisteringTextDocumentDto
     */
    private function runService(array $requestData): ResultRegisteringTextDocumentDto
    {
        $requestDto = new NewMagazineDto(
            $requestData['title'],
            $requestData['year'],
            $requestData['author_id'],
            $requestData['number'],
        );

        return $this->arrivalNewTextDocumentService->registerMagazine($requestDto);
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
            $err[] = 'Данные о новом журнале не являются массивом';
        } else {
            if (false === array_key_exists('title', $requestData)) {
                $err[] = 'Отсутствует информация о заголовке журнала';
            } elseif (false === is_string($requestData['title'])) {
                $err[] = 'Заголовок журнала должен быть строкой';
            } elseif ('' === trim($requestData['title'])) {
                $err[] = 'Заголовок журнала не может быть пустой строкой';
            }

            if (false === array_key_exists('year', $requestData)) {
                $err[] = 'Отсутствует информация о годе издания журнала';
            } elseif (false === is_int($requestData['year'])) {
                $err[] = 'Год издания журнала должен быть целым числом';
            } elseif ($requestData['year'] <= 0) {
                $err[] = 'Год издания журнала не может быть меньше или равен нуля';
            }

            if (false === array_key_exists('author_id', $requestData)) {
                $err[] = 'Отсутствует информация о id автора журнала';
            } elseif (null !== $requestData['author_id'] && false === is_int($requestData['author_id'])) {
                $err[] = 'Id автора должно быть целым числом либо иметь значение null';
            }

            if (false === array_key_exists('number', $requestData)) {
                $err[] = 'Отсутствует информация о номере журнала';
            } elseif (false === is_int($requestData['number'])) {
                $err[] = 'Номер журнала должен быть целым числом';
            } elseif ($requestData['number'] <= 0) {
                $err[] = 'Номер журнала не может быть меньше или равен нуля';
            }
        }

        return $err;
    }
}
