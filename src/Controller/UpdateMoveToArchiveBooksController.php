<?php

namespace EfTech\BookLibrary\Controller;

use EfTech\BookLibrary\Exception;
use EfTech\BookLibrary\Infrastructure\Controller\ControllerInterface;
use EfTech\BookLibrary\Infrastructure\Db\ConnectionInterface;
use EfTech\BookLibrary\Infrastructure\http\ServerResponseFactory;
use EfTech\BookLibrary\Service\ArchiveTextDocumentService\ArchivingResultDto;
use EfTech\BookLibrary\Service\ArchiveTextDocumentService\Exception\TextDocumentNotFoundException;
use EfTech\BookLibrary\Service\ArchivingTextDocumentService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class UpdateMoveToArchiveBooksController implements ControllerInterface
{
    private ServerResponseFactory $serverResponseFactory;
    /** Сервис архивации документов
     * @var ArchivingTextDocumentService
     */
    private ArchivingTextDocumentService $archivingTextDocumentService;
    private ConnectionInterface $connection;

    /**
     * @param ArchivingTextDocumentService $archivingTextDocumentService
     * @param ServerResponseFactory $serverResponseFactory
     * @param ConnectionInterface $connection
     */
    public function __construct(
        ArchivingTextDocumentService $archivingTextDocumentService,
        \EfTech\BookLibrary\Infrastructure\http\ServerResponseFactory $serverResponseFactory,
        \EfTech\BookLibrary\Infrastructure\Db\ConnectionInterface $connection
    ) {
        $this->archivingTextDocumentService = $archivingTextDocumentService;
        $this->serverResponseFactory = $serverResponseFactory;
        $this->connection = $connection;
    }

    /**
     * @inheritDoc
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $this->connection->beginTransaction();
            $attributes = $request->getAttributes();
            if (false === array_key_exists('id', $attributes)) {
                throw new Exception\RuntimeException('there is no information about the id of the text document');
            }
            $resultDto = $this->archivingTextDocumentService->archive((int)$attributes['id']);
            $httpCode = 200;
            $jsonData = $this->buildJsonData($resultDto);
            $this->connection->commit();
        } catch (TextDocumentNotFoundException $e) {
            $this->connection->rollBack();
            $httpCode = 404;
            $jsonData = ['status' => 'fail', 'message' => $e->getMessage()];
        } catch (Throwable $e) {
            $this->connection->rollBack();
            $httpCode = 500;
            $jsonData = ['status' => 'fail', 'message' => $e->getMessage()];
        }

        return $this->serverResponseFactory->createJsonResponse($httpCode, $jsonData);
    }

    /** Подготавливает данные для успешного ответа на основе dto сервиса
     * @param ArchivingResultDto $resultDto
     * @return array
     */
    private function buildJsonData(ArchivingResultDto $resultDto): array
    {
        return [
            'id' => $resultDto->getId(),
            'status' => $resultDto->getStatus(),
            'title_for_printing' => $resultDto->getTitleForPrinting()
        ];
    }
}
