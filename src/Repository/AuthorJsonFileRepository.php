<?php

namespace EfTech\BookLibrary\Repository;

use EfTech\BookLibrary\Entity\Author;
use EfTech\BookLibrary\Entity\AuthorRepositoryInterface;
use EfTech\BookLibrary\Infrastructure\DataLoader\DataLoaderInterface;

class AuthorJsonFileRepository implements AuthorRepositoryInterface
{
    /** Данные о авторах
     * @var array|null
     */
    private ?array $data = null;
    /**
     *
     *
     * @var string
     */
    private string $pathToAuthors;

    /**
     *
     *
     * @var DataLoaderInterface
     */
    private DataLoaderInterface $dataLoader;

    /**
     * @param string $pathToAuthors
     * @param DataLoaderInterface $dataLoader
     */
    public function __construct(string $pathToAuthors, DataLoaderInterface $dataLoader)
    {
        $this->pathToAuthors = $pathToAuthors;
        $this->dataLoader = $dataLoader;
    }

    /**
     * @return array
     */

    private function loadData(): array
    {
        if (null === $this->data) {
            $this->data = $this->dataLoader->loadData($this->pathToAuthors);
        }
        return $this->data;
    }

    public function findBy(array $criteria): array
    {
        $authors = $this->loadData();
        $foundAuthors = [];
        foreach ($authors as $author) {
            if (array_key_exists('surname', $criteria)) {
                $authorMeetsSearchCriteria = $criteria['surname'] === $author['surname'];
            } else {
                $authorMeetsSearchCriteria = true;
            }
            if ($authorMeetsSearchCriteria && array_key_exists('id', $criteria)) {
                $authorMeetsSearchCriteria = $criteria['id'] === $author['id'];
            }
            if ($authorMeetsSearchCriteria) {
                $authorObj = Author::createFromArray($author);
                $foundAuthors[] = $authorObj;
            }
        }

        return $foundAuthors;
    }
}
