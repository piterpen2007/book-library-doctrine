<?php

namespace EfTech\BookLibrary\Repository;

use Doctrine\ORM\EntityRepository;
use EfTech\BookLibrary\Entity\MagazineNumber;
use EfTech\BookLibrary\Entity\MagazineNumberRepositoryInterface;

/**
 *
 * Реализация работы репозитария номеров журналов на основе doctrine
 */
class MagazineNumberDoctrineRepository extends EntityRepository implements
    MagazineNumberRepositoryInterface
{
    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param null $limit
     * @param null $offset
     * @return MagazineNumber[]
     */
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): array
    {
        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }


    /**
     * @inheritDoc
     */
    public function nextId(): int
    {
        return $this->getClassMetadata(MagazineNumber::class)
            ->idGenerator->generateId($this->getEntityManager(), null);
    }

    /**
     * @inheritDoc
     */
    public function add(MagazineNumber $entity): MagazineNumber
    {
        $this->getEntityManager()->persist($entity);
        return $entity;
    }
}