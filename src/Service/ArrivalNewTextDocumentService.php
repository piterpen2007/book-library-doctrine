<?php

namespace EfTech\BookLibrary\Service;

use EfTech\BookLibrary\Entity\AbstractTextDocument;
use EfTech\BookLibrary\Entity\AuthorRepositoryInterface;
use EfTech\BookLibrary\Entity\Book;
use EfTech\BookLibrary\Entity\Magazine;
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
    /** Репозиторий для работы с текстовыми документами
     *
     * @var TextDocumentRepositoryInterface
     */
    private TextDocumentRepositoryInterface $textDocumentRepository;
    /** Репозиторий для работы с авторами
     * @var AuthorRepositoryInterface
     */
    private AuthorRepositoryInterface $authorRepository;

    /**
     * @param TextDocumentRepositoryInterface $textDocumentRepository
     * @param AuthorRepositoryInterface $authorRepository
     */
    public function __construct(
        TextDocumentRepositoryInterface $textDocumentRepository,
        AuthorRepositoryInterface $authorRepository
    ) {
        $this->textDocumentRepository = $textDocumentRepository;
        $this->authorRepository = $authorRepository;
    }

    public function registerBook(NewBookDto $bookDto): ResultRegisteringTextDocumentDto
    {
        $authorId = $bookDto->getAuthorId();

        $authorsCollection = $this->authorRepository->findBy(['id' => $authorId]);

        if (1 !== count($authorsCollection)) {
            throw new RuntimeException(
                'Нельзя зарегистрировать книгу с author_id = ' . $authorId . '. Автор с данным id  не найден.'
            );
        }
        $author = current($authorsCollection);

        $entity = new Book(
            $this->textDocumentRepository->nextId(),
            $bookDto->getTitle(),
            $bookDto->getYear(),
            $author,
            [],
            AbstractTextDocument::STATUS_IN_STOCK
        );

        $this->textDocumentRepository->add($entity);


        return new ResultRegisteringTextDocumentDto(
            $entity->getId(),
            $entity->getTitleForPrinting(),
            $entity->getStatus()
        );
    }

    public function registerMagazine(NewMagazineDto $magazineDto): ResultRegisteringTextDocumentDto
    {
        $authorId = $magazineDto->getAuthorId();
        if (null !== $authorId) {
            $authorsCollection = $this->authorRepository->findBy(['id' => $authorId]);
            if (1 !== count($authorsCollection)) {
                throw new RuntimeException(
                    'Нельзя зарегистрировать журнал с author_id = ' . $authorId . '. Автор с данным id  не найден.'
                );
            }
            $author = current($authorsCollection);
        } else {
            $author = null;
        }

        $entity = new Magazine(
            $this->textDocumentRepository->nextId(),
            $magazineDto->getTitle(),
            $magazineDto->getYear(),
            $author,
            $magazineDto->getNumber(),
            [],
            AbstractTextDocument::STATUS_IN_STOCK
        );

        $this->textDocumentRepository->add($entity);


        return new ResultRegisteringTextDocumentDto(
            $entity->getId(),
            $entity->getTitleForPrinting(),
            $entity->getStatus()
        );
    }
}
