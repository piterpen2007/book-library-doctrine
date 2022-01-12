<?php

namespace EfTech\BookLibrary\Service;

use EfTech\BookLibrary\Entity\Author;
use EfTech\BookLibrary\Entity\AuthorRepositoryInterface;
use EfTech\BookLibrary\Infrastructure\DataLoader\DataLoaderInterface;
use EfTech\BookLibrary\Infrastructure\Logger\LoggerInterface;
use EfTech\BookLibrary\Service\SearchAuthorsService\AuthorDto;
use EfTech\BookLibrary\Service\SearchAuthorsService\SearchAuthorsCriteria;
use Exception;
use JsonException;

/**
 *
 *
 * @package EfTech\BookLibrary\Service
 */
class SearchAuthorsService
{

    private AuthorRepositoryInterface $authorRepository;
    /**
     *
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;


    /**
     * @param LoggerInterface $logger
     * @param string $pathToAuthors
     * @param DataLoaderInterface $dataLoader
     * @param AuthorRepositoryInterface $authorRepository
     */
    public function __construct(LoggerInterface $logger , AuthorRepositoryInterface $authorRepository)
    {
        $this->logger = $logger;
        $this->authorRepository = $authorRepository;
    }

    /**
     * @param SearchAuthorsCriteria $searchCriteria
     * @return AuthorDto[]
     * @throws JsonException
     */
    public function search(SearchAuthorsCriteria $searchCriteria):array
    {
        $criteria = $this->searchCriteriaToArray($searchCriteria);
        $entitiesCollection = $this->authorRepository->findBy($criteria);
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
     */
    private function searchCriteriaToArray(SearchAuthorsCriteria $searchCriteria):array
    {
        $criteriaForRepository = [
            'id' => $searchCriteria->getId(),
            'surname' => $searchCriteria->getSurname()
        ];

        return array_filter($criteriaForRepository, static function($v):bool {return null !== $v;});
    }


}
