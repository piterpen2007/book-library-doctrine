<?php

namespace EfTech\BookLibrary\Service\SearchAuthorsService;
use EfTech\BookLibrary\Entity\Author;
use EfTech\BookLibrary\Infrastructure\DataLoader\DataLoaderInterface;
use EfTech\BookLibrary\Infrastructure\Logger\LoggerInterface;

use Exception;
use JsonException;

/**
 *
 *
 * @package EfTech\BookLibrary\Service
 */
class SearchAuthorsService
{
    /**
     *
     *
     * @var DataLoaderInterface
     */
    private DataLoaderInterface $dataLoader;
    /**
     *
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;
    /**
     *
     *
     * @var string
     */
    private string $pathToAuthors;

    /**
     * @param DataLoaderInterface $dataLoader
     * @param LoggerInterface $logger
     * @param string $pathToAuthors
     */
    public function __construct(LoggerInterface $logger ,string $pathToAuthors, DataLoaderInterface $dataLoader)
    {
        $this->dataLoader = $dataLoader;
        $this->logger = $logger;
        $this->pathToAuthors = $pathToAuthors;
    }

    /**
     *
     *
     * @return array
     * @throws JsonException
     */

    private function loadData():array
    {
        return $this->dataLoader->loadData($this->pathToAuthors);
    }

    /**
     * @param SearchAuthorsCriteria $searchCriteria
     * @return AuthorDto[]
     * @throws JsonException
     */
    public function search(SearchAuthorsCriteria $searchCriteria):array
    {
        $entitiesCollection = $this->searchEntity($searchCriteria);
        $dtoCollection = [];
        foreach ($entitiesCollection as $entity) {
            $dtoCollection[] = $this->createDto($entity);
        }
        $this->logger->log( 'found authors: ' . count($entitiesCollection));
        return $dtoCollection;
    }
    /**
     * Создание dto Автора
     * @param Author $author
     * @return AuthorDto
     */
    private function createDto(Author $author): AuthorDto
    {
        return new AuthorDto(
            $author->getId(),
            $author->getName(),
            $author->getSurname(),
            $author->getBirthday(),
            $author->getCountry()
        );
    }


    /**
     * @param SearchAuthorsCriteria $searchCriteria
     * @return array
     * @throws JsonException
     * @throws Exception
     */
    private function searchEntity(SearchAuthorsCriteria $searchCriteria):array
    {
        $authors = $this->loadData();
        $foundAuthors = [];
        foreach ($authors as $author) {
            if (null !== $searchCriteria->getSurname()) {
                $authorMeetsSearchCriteria = $searchCriteria->getSurname() === $author['surname'];
            } else {
                $authorMeetsSearchCriteria = true;
            }
            if ($authorMeetsSearchCriteria && null !== $searchCriteria->getId()) {
                $authorMeetsSearchCriteria = $searchCriteria->getId() === (string)$author['id'];
            }
            if ($authorMeetsSearchCriteria) {
                $authorObj = Author::createFromArray($author);
                $foundAuthors[] = $authorObj;
            }
        }
        $this->logger->log( 'found authors:' . count($foundAuthors));
        return $foundAuthors;
    }
}
