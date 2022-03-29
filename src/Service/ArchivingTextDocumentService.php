<?php

namespace EfTech\BookLibrary\Service;

use EfTech\BookLibrary\Entity\AbstractTextDocument;
use EfTech\BookLibrary\Entity\TextDocumentRepositoryInterface;
use EfTech\BookLibrary\Service\ArchiveTextDocumentService\ArchivingResultDto;
use EfTech\BookLibrary\Service\ArchiveTextDocumentService\Exception\TextDocumentNotFoundException;

class ArchivingTextDocumentService
{
    /** Репозиторий для работы с текстовыми документами
     * @var TextDocumentRepositoryInterface
     */
    private TextDocumentRepositoryInterface $textDocumentRepository;

    /**
     * @param TextDocumentRepositoryInterface $textDocumentRepository
     */
    public function __construct(TextDocumentRepositoryInterface $textDocumentRepository)
    {
        $this->textDocumentRepository = $textDocumentRepository;
    }

    /** Архивирует текстовой документ с заданным id
     * @param int $textDocumentId - id текстового документа
     * @return ArchivingResultDto -
     */
    public function archive(int $textDocumentId): ArchivingResultDto
    {
        $entities = $this->textDocumentRepository->findBy(['id' => $textDocumentId]);
        if (1 !== count($entities)) {
            throw new TextDocumentNotFoundException(
                "Не удалось отправить документ в архив. Документ с id='$textDocumentId' не найден."
            );
        }
        /** @var $entity AbstractTextDocument */
        $entity = current($entities);
        $entity->moveToArchive();


        return new ArchivingResultDto($entity->getId(), $entity->getTitleForPrinting(), $entity->getStatus());
    }
}
