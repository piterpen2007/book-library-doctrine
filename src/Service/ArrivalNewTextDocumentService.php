<?php

namespace EfTech\BookLibrary\Service;

use DateTimeImmutable;
use EfTech\BookLibrary\Entity\Author;
use EfTech\BookLibrary\Entity\AuthorRepositoryInterface;
use EfTech\BookLibrary\Entity\Book;
use EfTech\BookLibrary\Entity\Magazine;
use EfTech\BookLibrary\Entity\TextDocument\Status;
use EfTech\BookLibrary\Entity\TextDocumentRepositoryInterface;
use EfTech\BookLibrary\Service\ArchiveTextDocumentService\Exception\RuntimeException;
use EfTech\BookLibrary\Service\ArrivalNewTextDocumentService\NewBookDto;
use EfTech\BookLibrary\Service\ArrivalNewTextDocumentService\NewMagazineDto;
use EfTech\BookLibrary\Service\ArrivalNewTextDocumentService\ResultRegisteringTextDocumentDto;

/**
 * Сервис регистрации книг/журналов
 */
final class ArrivalNewTextDocumentService
{
    /**
     * Репозиторий для работы с текстовыми документами
     *
     * @var TextDocumentRepositoryInterface
     */
    private TextDocumentRepositoryInterface $textDocumentRepository;

    /**
     * Репозиторий для работы с авторами
     *
     * @var AuthorRepositoryInterface
     */
    private AuthorRepositoryInterface $authorRepository;

    /**
     * @param TextDocumentRepositoryInterface $textDocumentRepository - Репозиторий для работы с текстовыми документами
     * @param AuthorRepositoryInterface       $authorRepository       - Репозиторий для работы с авторами
     */
    public function __construct(
        TextDocumentRepositoryInterface $textDocumentRepository,
        AuthorRepositoryInterface $authorRepository
    ) {
        $this->textDocumentRepository = $textDocumentRepository;
        $this->authorRepository = $authorRepository;
    }

    /**
     * Загружает сущности авторов по их идентификаторам
     *
     * @param array $authorIdList
     *
     * @return array
     */
    private function loadAuthorEntities(array $authorIdList): array
    {
        if (0 === count($authorIdList)) {
            return [];
        }

        $authorsCollection = $this->authorRepository->findBy(['list_id' => $authorIdList]);

        if (count($authorsCollection) !== count($authorIdList)) {
            $actualCurrentIdList = array_map(static function (Author $a) {
                return $a->getId();
            }, $authorsCollection);
            $unFoundId = implode(', ', array_diff($authorIdList, $actualCurrentIdList));
            $errMsg = "Нельзя зарегистрировать текстовой документ с author_id = '$unFoundId'";
            throw new RuntimeException($errMsg);
        }
        return $authorsCollection;
    }

    /**
     * Регистрация новой книги
     *
     * @param NewBookDto $bookDto
     *
     * @return ResultRegisteringTextDocumentDto
     */
    public function registerBook(NewBookDto $bookDto): ResultRegisteringTextDocumentDto
    {
        $entity = new Book(
            $this->textDocumentRepository->nextId(),
            $bookDto->getTitle(),
            DateTimeImmutable::createFromFormat('Y', $bookDto->getYear()),
            $this->loadAuthorEntities($bookDto->getAuthorIds()),
            [],
            new Status(Status::STATUS_IN_STOCK)
        );
        $this->textDocumentRepository->add($entity);

        return new ResultRegisteringTextDocumentDto(
            $entity->getId(),
            $entity->getTitleForPrinting(),
            $entity->getStatus()->getName()
        );
    }

    /**
     * Регистрация нового журнала
     *
     * @param NewMagazineDto $magazineDto
     *
     * @return ResultRegisteringTextDocumentDto
     */
    public function registerMagazine(NewMagazineDto $magazineDto): ResultRegisteringTextDocumentDto
    {
        $entity = new Magazine(
            $this->textDocumentRepository->nextId(),
            $magazineDto->getTitle(),
            DateTimeImmutable::createFromFormat('Y', $magazineDto->getYear()),
            $this->loadAuthorEntities($magazineDto->getAuthorIds()),
            $magazineDto->getNumber(),
            [],
            new Status(Status::STATUS_IN_STOCK)
        );
        $this->textDocumentRepository->add($entity);

        return new ResultRegisteringTextDocumentDto(
            $entity->getId(),
            $entity->getTitleForPrinting(),
            $entity->getStatus()->getName()
        );
    }

}
