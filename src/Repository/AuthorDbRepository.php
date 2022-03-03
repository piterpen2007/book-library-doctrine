<?php

namespace EfTech\BookLibrary\Repository;

use DateTimeImmutable;
use EfTech\BookLibrary\Entity\Author;
use EfTech\BookLibrary\Entity\AuthorRepositoryInterface;
use EfTech\BookLibrary\Exception\RuntimeException;
use EfTech\BookLibrary\Infrastructure\Db\ConnectionInterface;

class AuthorDbRepository implements AuthorRepositoryInterface
{
    private const ALLOWED_CRITERIA = [
        'id',
        'name',
        'surname',
        'birthday',
        'country',
        'list_id'
    ];
    /**
     *  Соединение с бд
     *
     * @var ConnectionInterface
     */
    private ConnectionInterface $connection;

    /**
     * @param ConnectionInterface $connection
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    public function findBy(array $criteria): array
    {
        $this->validateCriteria($criteria);

        $whereParts = [];
        $whereParams = [];


        foreach ($criteria as $criteriaName => $criteriaValue) {
            if ('list_id' === $criteriaName) {
                if (false === is_array($criteriaValue)) {
                    throw new RuntimeException('Некорректный список id авторов');
                }
                $idParts = [];
                foreach ($criteriaValue as $index => $idValue) {
                    $idParts[] = ":id_$index";
                    $whereParams["id_$index"] = $idValue;
                }
                if (count($idParts) > 0) {
                    $whereParts[] = 'id IN (' . implode(', ', $idParts) . ')';
                } else {
                    $whereParts[] = "$criteriaName = :$criteriaName";
                    $whereParams[$criteriaName] = $criteriaValue;
                }
            }

        }


        $sql = <<<EOF
SELECT 
    id, name, surname, birthday, country
FROM authors
EOF;

        if (count($whereParts) > 0) {
            $sql .= ' WHERE ' . implode(' AND ', $whereParts);
        }

        $stmt = $this->connection->prepare($sql);
        $stmt->execute($whereParams);

        $authorsData = $stmt->fetchAll();

        $foundAuthors = [];

        foreach ($authorsData as $authorItem) {
            $birthdayAuthor = DateTimeImmutable::createFromFormat('Y-m-d', $authorItem['birthday']);
            $authorItem['birthday'] = $birthdayAuthor->format('d.m.Y');
            $authorObj = Author::createFromArray($authorItem);
            $foundAuthors[$authorObj->getId()] = $authorObj;
        }

        return $foundAuthors;
    }

    /**
     *  Валидация критериев поиска
     *
     * @param array $criteria
     * @return void
     */
    private function validateCriteria(array $criteria): void
    {
        $invalidCriteria = array_diff(array_keys($criteria), self::ALLOWED_CRITERIA);
        if (count($invalidCriteria) > 0) {
            $errMsg = 'неподдерживаемые критерии поиска авторов: ' . implode(', ', $invalidCriteria);
            throw new RuntimeException($errMsg);
        }
    }
}
