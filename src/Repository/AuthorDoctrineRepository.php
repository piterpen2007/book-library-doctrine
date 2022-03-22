<?php

namespace EfTech\BookLibrary\Repository;

use Doctrine\ORM\EntityRepository;
use EfTech\BookLibrary\Entity\AuthorRepositoryInterface;

class AuthorDoctrineRepository extends EntityRepository implements
    AuthorRepositoryInterface
{
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): array
    {
        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }

}