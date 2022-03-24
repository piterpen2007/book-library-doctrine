<?php

namespace EfTech\BookLibrary\Repository;

use Doctrine\ORM\EntityRepository;
use EfTech\BookLibrary\Entity\AuthorRepositoryInterface;

class AuthorDoctrineRepository extends EntityRepository implements
    AuthorRepositoryInterface
{
    /**
     * Критерии поиска для замены
     */
    private const REPLACED_CRITERIA = [
        'surname' => 'fullName.surname',
        'name'    => 'fullName.name',
    ];

    /**
     * @inheritDoc
     */
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): array
    {
        $preparedCriteria = [];
        foreach ($criteria as $key => $value) {
            if (array_key_exists($key, self::REPLACED_CRITERIA)) {
                $preparedCriteria[self::REPLACED_CRITERIA[$key]] = $value;
            } else {
                $preparedCriteria[$key] = $value;
            }
        }

        return parent::findBy($preparedCriteria, $orderBy, $limit, $offset);
    }

}
