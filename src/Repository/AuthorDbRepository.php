<?php

namespace EfTech\BookLibrary\Repository;

use DateTimeImmutable;
use EfTech\BookLibrary\Entity\Author;
use EfTech\BookLibrary\Entity\AuthorRepositoryInterface;
use EfTech\BookLibrary\Exception\RuntimeException;
use EfTech\BookLibrary\Infrastructure\Db\ConnectionInterface;

/**
 * Реализация репозитория для сущности TextDocument. Данные хранятся в BD
 */
final class AuthorDbRepository implements AuthorRepositoryInterface
{
    /**
     * Поддерживаемые критерии поиска
     */
    private const ALLOWED_CRITERIA = [
        'id',
        'name',
        'birthday',
        'surname',
        'country',
        'list_id',
    ];

    /**
     * Валидация критериев поиска
     *
     * @param array $criteria - Входные критерии
     *
     * @return void
     */
    private function validateCriteria(array $criteria): void
    {
        $invalidCriteria = array_diff(array_keys($criteria), self::ALLOWED_CRITERIA);

        if (0 < count($invalidCriteria)) {
            $errMsg = 'Неподдерживаемые критерии поиска авторов ' . implode(', ', $invalidCriteria);
            throw new RuntimeException($errMsg);
        }
    }

    /**
     * Соединение с БД
     *
     * @var ConnectionInterface
     */
    private ConnectionInterface $connection;

    /**
     * @param ConnectionInterface $connection - Соединение с БД
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @inheritDoc
     */
    public function findBy(array $criteria): array
    {
        $this->validateCriteria($criteria);

        $whereParts = [];
        $whereParams = [];

        foreach ($criteria as $criteriaName => $criteriaValue) {
            if ('list_id' === $criteriaName) {
                if (false === is_array($criteriaValue)) {
                    throw new RuntimeException('list_id должен быть массивом');
                }

                $idParts = [];
                foreach ($criteriaValue as $index => $idValue) {
                    $idParts[] = ":id_$index";
                    $whereParams["id_$index"] = $idValue;
                }
                if (0 < count($idParts)) {
                    $whereParts[] = 'id in (' . implode(', ', $idParts) . ')';
                }
            } else {
                $whereParts[] = "$criteriaName=:$criteriaName";
                $whereParams[$criteriaName] = $criteriaValue;
            }
        }

        $sql = <<<EOF
SELECT
id, name, surname, birthday, country
FROM authors
EOF;
        if (0 < count($whereParts)) {
            $sql .= ' where ' . implode(' and ', $whereParts);
        }

        $statement = $this->connection->prepare($sql);
        $statement->execute($whereParams);
        $authorsData = $statement->fetchAll();

        $foundAuthors = [];

        foreach ($authorsData as $authorsItem) {
            $birthdayAuthor = DateTimeImmutable::createFromFormat('Y-m-d', $authorsItem['birthday']);
            $authorsItem['birthday'] = $birthdayAuthor->format('d.m.Y');
            $authorObj = Author::createFromArray($authorsItem);
            $foundAuthors[$authorObj->getId()] = $authorObj;
        }

        return $foundAuthors;
    }
}
