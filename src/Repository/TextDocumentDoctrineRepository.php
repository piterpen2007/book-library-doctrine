<?php

namespace EfTech\BookLibrary\Repository;

use Doctrine\ORM\EntityRepository;
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
     * @param array $criteria
     * @param array|null $orderBy
     * @param $limit
     * @param $offset
     * @return array|AbstractTextDocument[]|object[]
     */
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): array
    {
        return parent::findBy($criteria, $orderBy, $limit, $offset); // TODO: Change the autogenerated stub
    }


    /**
     * @inheritDoc
     */
    public function save(AbstractTextDocument $entity): AbstractTextDocument
    {
        throw new RuntimeException('Сохранение сущности текстовой документ не реализовано');
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