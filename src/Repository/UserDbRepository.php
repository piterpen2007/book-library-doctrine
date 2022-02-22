<?php

namespace EfTech\BookLibrary\Repository;

use EfTech\BookLibrary\Entity\AbstractTextDocument;
use EfTech\BookLibrary\Entity\User;
use EfTech\BookLibrary\Entity\UserRepositoryInterface;
use EfTech\BookLibrary\Exception\RuntimeException;
use EfTech\BookLibrary\Infrastructure\Auth\UserDataStorageInterface;
use EfTech\BookLibrary\Infrastructure\Db\ConnectionInterface;
use EfTech\BookLibrary\Repository\UserRepository\UserDataProvider;

/**
 * Реализация репозитория для юзера
 */
final class UserDbRepository implements UserRepositoryInterface, UserDataStorageInterface
{

    private ConnectionInterface $connection;

    /**
     * @param ConnectionInterface $connection
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }


    /** Поиск сущностей по заданному критерию
     *
     * @param array $criteria
     * @return AbstractTextDocument[]
     */
    public function findBy(array $criteria): array
    {
        $sql = <<<EOF
        SELECT id, login, password FROM users 
EOF;
        $whereParts = [];
        foreach ($criteria as $fieldName => $fieldValue) {
            $whereParts[] = "$fieldName='$fieldValue'";
        }
        if (count($whereParts) > 0) {
            $sql .= ' where ' . implode(' and ', $whereParts);
        }

        $dataFromDb = $this->connection->query($sql)->fetchAll();

        $foundEntities = [];

        foreach ($dataFromDb as $item) {
            $foundEntities[] = new UserDataProvider($item['id'], $item['login'], $item['password']);
        }

        return $foundEntities;
    }

    /** Поиск пользователя по логину
     * @param string $login
     * @return User|null
     */
    public function findUserByLogin(string $login): ?UserDataProvider
    {
        $entities = $this->findBy(['login' => $login]);
        $countEntities = count($entities);

        if ($countEntities > 1) {
            throw new RuntimeException('Найдены пользователи с дублирубщимися логинами');
        }
        return (0 === $countEntities) ? null : current($entities);
    }

}
