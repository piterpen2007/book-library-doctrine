<?php

namespace EfTech\BookLibrary\Service;

use EfTech\BookLibrary\Entity\AbstractTextDocument;
use EfTech\BookLibrary\Entity\Author;
use EfTech\BookLibrary\Entity\Book;
use EfTech\BookLibrary\Entity\Magazine;
use EfTech\BookLibrary\Entity\TextDocumentRepositoryInterface;
use EfTech\BookLibrary\Exception\RuntimeException;
use Psr\Log\LoggerInterface;
use EfTech\BookLibrary\Service\SearchTextDocumentService\AuthorDto;
use EfTech\BookLibrary\Service\SearchTextDocumentService\SearchTextDocumentServiceCriteria;
use EfTech\BookLibrary\Service\SearchTextDocumentService\TextDocumentDto;

/**
 *
 *
 * @package EfTech\BookLibrary\Service
 */
class SearchTextDocumentService
{
    /**
     *
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;
    /** Наш репозиторий текстовых документов
     * @var TextDocumentRepositoryInterface
     */
    private TextDocumentRepositoryInterface $textDocumentRepository;


    /**
     * @param LoggerInterface $logger
     * @param TextDocumentRepositoryInterface $textDocumentRepository
     */
    public function __construct(
        LoggerInterface $logger,
        TextDocumentRepositoryInterface $textDocumentRepository
    ) {
        $this->logger = $logger;
        $this->textDocumentRepository = $textDocumentRepository;
    }


    /**
     * Возвращает тип текстового документа
     *
     * @param $textDocument AbstractTextDocument
     *
     * @return string
     */
    private function getTextDocumentType(AbstractTextDocument $textDocument): string
    {
        if ($textDocument instanceof Magazine) {
            $type = TextDocumentDto::TYPE_MAGAZINE;
        } elseif ($textDocument instanceof Book) {
            $type = TextDocumentDto::TYPE_BOOK;
        } else {
            throw new RuntimeException(' ');
        }
        return $type;
    }

    /**
     * Создание dto
     *
     * @param AbstractTextDocument $textDocument
     *
     * @return TextDocumentDto
    SearchTextDocumentService\TextDocumentDto
     */
    private function createDto(AbstractTextDocument $textDocument): TextDocumentDto
    {
        $authors = array_map(static function (Author $a) {
            return new AuthorDto(
                $a->getId(),
                $a->getName(),
                $a->getSurname(),
                $a->getBirthday(),
                $a->getCountry()
            );
        }, $textDocument->getAuthors());

        return new
        TextDocumentDto(
            $this->getTextDocumentType($textDocument),
            $textDocument->getId(),
            $textDocument->getTitle(),
            $textDocument->getTitleForPrinting(),
            $textDocument->getYear(),
            $authors,
            $textDocument instanceof Magazine ? $textDocument->getNumber() : null
        );
    }

    /**
     *
     *
     * @param SearchTextDocumentServiceCriteria $searchCriteria
     * @return TextDocumentDto[]
     */
    public function search(SearchTextDocumentServiceCriteria $searchCriteria): array
    {
        $criteria = $this->searchCriteriaToArray($searchCriteria);
        $entitiesCollection = $this->textDocumentRepository->findBy($criteria);
        $dtoCollection = [];
        foreach ($entitiesCollection as $entity) {
            $dtoCollection[] = $this->createDto($entity);
        }
        $this->logger->debug("Найдено книг: " . count($entitiesCollection));
        return $dtoCollection;
    }

    /** Преобразует критерии поиска в массив
     * @param SearchTextDocumentServiceCriteria $searchCriteria
     * @return array
     */
    private function searchCriteriaToArray(SearchTextDocumentServiceCriteria $searchCriteria): array
    {
        $criteriaForRepository = [
            'author_surname' => $searchCriteria->getAuthorSurname(),
            'author_id' => $searchCriteria->getAuthorId(),
            'author_name' => $searchCriteria->getAuthorName(),
            'author_birthday' => $searchCriteria->getAuthorBirthday(),
            'author_country' => $searchCriteria->getAuthorCountry(),
            'id' => $searchCriteria->getId(),
            'title' => $searchCriteria->getTitle(),
            'year' => $searchCriteria->getYear(),
            'status' => $searchCriteria->getStatus(),
            'type' => $searchCriteria->getType()
        ];
        return array_filter($criteriaForRepository, static function ($v): bool {
            return null !== $v;
        });
    }
}
