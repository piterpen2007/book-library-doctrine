<?php

namespace EfTech\BookLibrary\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use EfTech\BookLibrary\Entity\AbstractTextDocument;
use EfTech\BookLibrary\Entity\TextDocumentRepositoryInterface;
use EfTech\BookLibrary\Exception\RuntimeException;

/**
 * Реализация репозитория работы с текстовым документом на основе репозитария доктрины
 */
class TextDocumentDoctrineRepository extends EntityRepository implements
    TextDocumentRepositoryInterface
{
    /**
     * Критерии поиска для замены
     */
    private const REPLACED_CRITERIA = [
        'surname' => 'fullName.surname',
        'name'    => 'fullName.name',
    ];

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param $limit
     * @param $offset
     * @return array|AbstractTextDocument[]|object[]
     */
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder->select(['t'])
            ->from(AbstractTextDocument::class, 't')
            ->leftJoin('t.authors', 'a');

        $this->buildWere($queryBuilder, $criteria);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Формируем условия поиска в запросе
     *
     * @param QueryBuilder $queryBuilder
     * @param array $criteria
     */
    public function buildWere(QueryBuilder $queryBuilder, array $criteria): void
    {
        if (0 === $this->count($criteria)) {
            return;
        }

        // WHERE AND
        $whereExprAnd = $queryBuilder->expr()->andX();

        foreach ($criteria as $criteriaName => $criteriaValue) {
            if (0 === strpos($criteriaName, 'author_')) {
                $preparedName = $this->prepareAuthorCriteria($criteriaName);
                $whereExprAnd->add($queryBuilder->expr()->eq("a.$preparedName", ":$criteriaName"));
            } else {
                $whereExprAnd->add($queryBuilder->expr()->eq("t.$criteriaName", ":$criteriaName"));
            }
        }
        $queryBuilder->where($whereExprAnd);
        $queryBuilder->setParameters($criteria);
    }

    /**
     *
     *
     * @param string $key
     * @return string
     */
    private function prepareAuthorCriteria(string $key): string
    {
        $propertyName = substr($key, 7);
        if (array_key_exists($propertyName, self::REPLACED_CRITERIA)) {
            $preparedCriteriaName = self::REPLACED_CRITERIA[$propertyName];
        } else {
            $preparedCriteriaName = $propertyName;
        }
        return $preparedCriteriaName;
    }

    public function nextId(): int
    {
        throw new RuntimeException('Генерация id для сущности текстовой документ не реализовано');
    }

    /**
     * @inheritDoc
     */
    public function add(AbstractTextDocument $entity): AbstractTextDocument
    {
        throw new RuntimeException('Добавление новых сущностей текстовой документ не реализовано');
    }
}
