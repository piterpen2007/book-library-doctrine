<?php

namespace EfTech\BookLibrary\Repository;

use Doctrine\ORM\EntityRepository;
use EfTech\BookLibrary\Entity\UserRepositoryInterface;
use EfTech\BookLibrary\Exception\RuntimeException;
use EfTech\BookLibrary\Infrastructure\Auth\UserDataStorageInterface;

class UserDoctrineRepository extends EntityRepository implements UserDataStorageInterface, UserRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function findUserByLogin(string $login): ?UserDataProvider
    {
        $entities = $this->findBy(['login' => $login]);
        $countEntities = count($entities);

        if ($countEntities > 1) {
            throw new RuntimeException('Найдены пользователи с дублирующимися логинами');
        }

        return 0 === $countEntities ? null : current($entities);
    }

    /**
     * @inheritDoc
     */
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): array
    {
        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }
}
