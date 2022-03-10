<?php

namespace EfTech\BookLibrary\Repository;

use DateTimeImmutable;
use EfTech\BookLibrary\Entity\Author;
use EfTech\BookLibrary\Entity\AuthorRepositoryInterface;
use EfTech\BookLibrary\Exception\RuntimeException;
use EfTech\BookLibrary\Infrastructure\Db\ConnectionInterface;
use EfTech\BookLibrary\ValueObject\Country;

/**
 * Реализация репозитория для сущности TextDocument. Данные хранятся в BD
 */
final class AuthorDbRepository implements AuthorRepositoryInterface
{
    /**
     * Поддерживаемые критерии поиска
     */
    private const ALLOWED_CRITERIA = [
        'id' => 'a.id',
        'name' => 'a.name',
        'birthday' => 'a.birthday',
        'surname' => 'a.surname',
        'country' => 'c.code2',
        'list_id' => null
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
        $invalidCriteria = array_diff(array_keys($criteria), array_keys(self::ALLOWED_CRITERIA));

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
                    $whereParts[] = 'a.id in (' . implode(', ', $idParts) . ')';
                }
            } else {
                $criteriaToSqlParts = self::ALLOWED_CRITERIA;

                $whereParts[] = "{$criteriaToSqlParts[$criteriaName]}=:$criteriaName";
                $whereParams[$criteriaName] = $criteriaValue;
            }
        }

        $sql = <<<EOF
SELECT
a.id as id, 
       a.name as name, 
       a.surname as surname, 
       a.birthday as birthday, 
       c.code2 as country_code2,
       c.code3 as country_code3,
       c.code as country_code,
       c.name as country_name
FROM authors as a 
JOIN country as c on c.id = a.country_id
EOF;
        if (0 < count($whereParts)) {
            $sql .= ' where ' . implode(' and ', $whereParts);
        }

        $statement = $this->connection->prepare($sql);
        $statement->execute($whereParams);
        $authorsData = $statement->fetchAll();

        $foundAuthors = [];

        foreach ($authorsData as $authorsItem) {
            $authorsItem['birthday'] = DateTimeImmutable::createFromFormat('Y-m-d', $authorsItem['birthday']);
            $authorsItem['country'] = new Country(
                $authorsItem['country_code2'],
                $authorsItem['country_code3'],
                $authorsItem['country_code'],
                $authorsItem['country_name']
            );
            $authorObj = Author::createFromArray($authorsItem);
            $foundAuthors[$authorObj->getId()] = $authorObj;
        }

        return $foundAuthors;
    }
}
