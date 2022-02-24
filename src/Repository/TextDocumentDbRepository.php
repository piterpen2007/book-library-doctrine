<?php

namespace EfTech\BookLibrary\Repository;

use DateTimeImmutable;
use EfTech\BookLibrary\Entity\AbstractTextDocument;
use EfTech\BookLibrary\Entity\Author;
use EfTech\BookLibrary\Entity\Magazine;
use EfTech\BookLibrary\Entity\TextDocumentRepositoryInterface;
use EfTech\BookLibrary\Exception\RuntimeException;
use EfTech\BookLibrary\Infrastructure\Db\ConnectionInterface;

class TextDocumentDbRepository implements TextDocumentRepositoryInterface
{
    private const ALLOWED_CRITERIA = [
        'author_surname',
        //'author_id',
        'id',
        'title'
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

    /**
     * @inheritDoc
     */
    public function findBy(array $criteria): array
    {
        $this->validateCriteria($criteria);

        $textDocumentData = $this->loadTextDocumentData($criteria);
        $authorEntities = $this->loadAuthorEntity($criteria,$textDocumentData);

        return $this->buildTextDocumentEntities($textDocumentData);
    }

    /**
     *  Загрузка данных о авторах
     *
     * @param array $criteria
     * @param array $textDocumentData
     * @return array
     */
    private function loadAuthorEntity(array $criteria, array $textDocumentData): array
    {
        $whereParts = [];
        $whereParams = [];
        $idListWhereParts = [];

        foreach ($textDocumentData as $textDocumentItem) {
            if (null !== $textDocumentItem['author_id']) {
                $valuePlaceholder = "id_{$textDocumentItem['author_id']}";

                if (false === array_key_exists($valuePlaceholder, $whereParams)) {
                    $idListWhereParts[] = "id=:$valuePlaceholder";
                    $whereParams[$valuePlaceholder] = $textDocumentItem['author_id'];
                }
            }
        }
        if (count($idListWhereParts) > 0) {
            $whereParts[] = '(' . implode(' OR ', $idListWhereParts) . ')';
        }

        foreach ($criteria as $criteriaName => $criteriaValue) {
            if (0 !== strpos($criteriaName, 'author_')) {
                continue;
            }
            $columnName = substr($criteriaName, 7);

            $whereParts[] = "$columnName =:$criteriaName";
            $whereParams[$columnName] = $criteriaValue;
        }
        if (0 === count($whereParts)) {
            return [];
        }
        $sql = <<<EOF
SELECT
    id, 
    name, 
    surname, 
    birthday, 
    country
FROM authors
EOF;
        $sql .= ' WHERE ' . implode(' AND ', $whereParts);

        $stmt = $this->connection->prepare($sql);
        $stmt->execute($whereParams);
        $authorsData = $stmt->fetchAll();

        $foundAuthors = [];

        foreach ($authorsData as $authorItem) {
            $birthdayAuthor = DateTimeImmutable::createFromFormat('Y-m-d',$authorItem['birthday']);
            $authorItem['birthday'] = $birthdayAuthor->format('d.m.Y');


            $authorObj = Author::createFromArray($authorItem);
            $foundAuthors[$authorObj->getId()] = $authorObj;
        }

        return $foundAuthors;

    }


    /**
     * Загружает данные о текстовых документах
     *
     * @param array $criteria
     * @return array
     */
    private function loadTextDocumentData(array $criteria): array
    {
        $sql = <<<EOF
SELECT 
    id, 
    title, 
    year, 
    author_id, 
    status,
    type, 
    number
FROM text_documents

EOF;
        $whereParts = [];
        $whereParams = [];


        foreach ($criteria as $criteriaName => $criteriaValue) {
            if (0 === strpos($criteriaName, 'author_')) {
                continue;
            }
            $whereParts[] = "$criteriaName = :$criteriaName";
            $whereParams[$criteriaName] = $criteriaValue;
        }

        if (count($whereParts) > 0) {
            $sql .= ' WHERE ' . implode(' AND ', $whereParts);
        }

        $stmt = $this->connection->prepare($sql);
        $stmt->execute($whereParams);

        return $stmt->fetchAll();
    }

    /**
     * @inheritDoc
     */
    public function save(AbstractTextDocument $entity): AbstractTextDocument
    {
        return $entity;
    }

    public function nextId(): int
    {
        return 0;
    }

    /**
     * @inheritDoc
     */
    public function add(AbstractTextDocument $entity): AbstractTextDocument
    {
        return $entity;
    }

    /**
     *  Делает сущности на основе данных из бд
     *
     * @param array $textDocumentData
     *
     * @return AbstractTextDocument[]
     */
    private function buildTextDocumentEntities(array $textDocumentData): array
    {
        $textDocumentEntities = [];

        foreach ($textDocumentData as $textDocumentItem) {
            $textDocumentItem['author'] = null;

            $yearDate = DateTimeImmutable::createFromFormat('Y-m-d', $textDocumentItem['year']);
            $textDocumentItem['year'] = (int)$yearDate->format('Y');
            $textDocumentItem['purchasePrices'] = [];
            if ('magazine' === $textDocumentItem['type']) {
                $textDocumentEntities[] = Magazine::createFromArray($textDocumentItem);
            }
        }
        return $textDocumentEntities;
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
            $errMsg = 'неподдерживаемые критерии поиска текстовых документов: ' . implode(', ', $invalidCriteria);
            throw new RuntimeException($errMsg);
        }
    }
}