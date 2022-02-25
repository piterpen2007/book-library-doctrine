<?php

namespace EfTech\BookLibrary\Repository;

use DateTimeImmutable;
use EfTech\BookLibrary\Entity\AbstractTextDocument;
use EfTech\BookLibrary\Entity\Author;
use EfTech\BookLibrary\Entity\Book;
use EfTech\BookLibrary\Entity\Magazine;
use EfTech\BookLibrary\Entity\TextDocumentRepositoryInterface;
use EfTech\BookLibrary\Exception\RuntimeException;
use EfTech\BookLibrary\Infrastructure\Db\ConnectionInterface;
use EfTech\BookLibrary\ValueObject\Currency;
use EfTech\BookLibrary\ValueObject\Money;
use EfTech\BookLibrary\ValueObject\PurchasePrice;
use phpDocumentor\Reflection\Types\Static_;

class TextDocumentDbRepository implements TextDocumentRepositoryInterface
{
    private const ALLOWED_CRITERIA = [
        'author_surname',
        'author_id',
        'author_name',
        'author_birthday',
        'author_country',
        'id',
        'title',
        'year',
        'status',
        'number',
        'type',
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
        $authorEntities = $this->loadAuthorEntity($criteria, $textDocumentData);
        $purchasePrices = $this->loadPurchasePrices($textDocumentData);

        return $this->buildTextDocumentEntities($criteria, $textDocumentData, $authorEntities, $purchasePrices);
    }

    /**
     * @param array $textDocumentData
     * @return PurchasePrice[]
     *
     */
    private function loadPurchasePrices(array $textDocumentData): array
    {
        $idListWhereParts = [];
        $whereParams = [];

        foreach ($textDocumentData as $textDocumentItem) {
            $idListWhereParts[] = "text_document_id=:id_{$textDocumentItem['id']}";
            $whereParams["id_{$textDocumentItem['id']}"] = $textDocumentItem['id'];
        }

        if (0 === count($idListWhereParts)) {
            return [];
        }

        $sql = <<<EOF
SELECT 
date, price, currency, text_document_id
FROM purchase_price
EOF;
        $sql .= ' WHERE ' . implode(' OR ', $idListWhereParts);

        $stmt = $this->connection->prepare($sql);
        $stmt->execute($whereParams);

        $purchasePricesData = $stmt->fetchAll();


        $foundPurchasePrices = [];

        foreach ($purchasePricesData as $purchasePrice) {
            $currencyName = 'RUB' === $purchasePrice['currency'] ? 'рубль' : 'неизвестно';

            $obj = new PurchasePrice(
                DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $purchasePrice['date']),
                new Money(
                    $purchasePrice['price'],
                    new Currency(
                        $purchasePrice['currency'],
                        $currencyName
                    )
                )
            );
            if (false === array_key_exists($purchasePrice['text_document_id'], $foundPurchasePrices)) {
                $foundPurchasePrices[$purchasePrice['text_document_id']] = [];
            }
            $foundPurchasePrices[$purchasePrice['text_document_id']][] = $obj;
        }

        return $foundPurchasePrices;
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

        $list = array_map(
            static function (array $a) {
                return $a['author_id'];
            },
            array_filter(
                $textDocumentData,
                static function (array $a) {
                    return isset($a['author_id']);
                }
            )
        );

        $authorIdList = array_unique($list);

        if (count($authorIdList) > 0) {
            $whereParams = array_combine(
                array_map(
                    static function(int $idx) {return ':id_' . $idx;},
                    range(1, count($authorIdList))
                ),
                $authorIdList
            );

            $whereParts[] = ' id IN (' . implode(', ', array_keys($whereParams)) . ')';
        }


        foreach ($criteria as $criteriaName => $criteriaValue) {
            if (0 !== strpos($criteriaName, 'author_')) {
                continue;
            }
            $columnName = substr($criteriaName, 7);

            $whereParts[] = "$columnName =:$criteriaName";
            $whereParams[$criteriaName] = $criteriaValue;
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
            $birthdayAuthor = DateTimeImmutable::createFromFormat('Y-m-d', $authorItem['birthday']);
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
        $sql = <<<EOF
UPDATE text_documents
SET 
    title = :title,
    year = :year,
    status = :status,
    number = :number,
    author_id = :author_id,
    type = :type
WHERE id = :id
EOF;
        $values = [
            'id' => $entity->getId(),
            'title' => $entity->getTitle(),
            'year' => "{$entity->getYear()}/01/01",
            'author_id' => null,
            'status' => $entity->getStatus(),
            'type' => null,
            'number' => null
        ];

        if ($entity instanceof Book) {
            $values['author_id'] = $entity->getAuthor()->getId();
            $values['type'] = 'book';
        } elseif ($entity instanceof Magazine) {
            $values['author_id'] = null === $entity->getAuthor() ? null : $entity->getAuthor()->getId();
            $values['type'] = 'magazine';
            $values['number'] = $entity->getNumber();
        } else {
            throw new RuntimeException('Текстовой документ данного типа не может быть добавлен');
        }
        $this->connection->prepare($sql)->execute($values);

        $sql = <<<EOF
DELETE FROM purchase_price WHERE text_document_id = :text_document_id
EOF;
        $this->connection->prepare($sql)->execute(['text_document_id' => $entity->getId()]);

        $sql = <<<EOF
INSERT INTO purchase_price
        (date, price, currency, text_document_id)
VALUES (:date, :price, :currency, :textDocumentId)
EOF;

        $stmt = $this->connection->prepare($sql);

        foreach ($entity->getPurchasePrices() as $purchasePrice) {
            $values = [
                'date' => $purchasePrice->getDate()->format('Y-m-d H:i:s'),
                'price' => $purchasePrice->getMoney()->getAmount(),
                'currency' => $purchasePrice->getMoney()->getCurrency()->getCode(),
                'textDocumentId' => $entity->getId()
            ];
            $stmt->execute($values);
        }
        return $entity;
    }

    public function nextId(): int
    {
        $sql = <<<EOF
SELECT 
    MAX(id) AS max_id
FROM text_documents
EOF;

        $maxId = current($this->connection->query($sql)->fetchAll())['max_id'];
        $maxId = null === $maxId ?? 0;

        return ((int)$maxId) + 1;
    }

    /**
     * @inheritDoc
     */
    public function add(AbstractTextDocument $entity): AbstractTextDocument
    {
        $sql = <<<EOF
INSERT INTO text_documents (id, title, year, author_id, status, type, number)
VALUES (
        :id, :title, :year, :author_id, :status, :type, :number
)
EOF;
        $values = [
            'id' => $entity->getId(),
            'title' => $entity->getTitle(),
            'year' => "{$entity->getYear()}/01/01",
            'author_id' => null,
            'status' => $entity->getStatus(),
            'type' => null,
            'number' => null
        ];

        if ($entity instanceof Book) {
            $values['author_id'] = $entity->getAuthor()->getId();
            $values['type'] = 'book';
        } elseif ($entity instanceof Magazine) {
            $values['author_id'] = null === $entity->getAuthor() ? null : $entity->getAuthor()->getId();
            $values['type'] = 'magazine';
            $values['number'] = $entity->getNumber();
        } else {
            throw new RuntimeException('Текстовой документ данного типа не может быть добавлен');
        }
        $this->connection->prepare($sql)->execute($values);

        $sql = <<<EOF
DELETE FROM purchase_price WHERE text_document_id = :text_document_id
EOF;

        return $entity;
    }

    /**
     *  Делает сущности на основе данных из бд
     *
     * @param array $criteria
     * @param array $textDocumentData
     * @param Author[] $authorEntities
     * @param PurchasePrice[] $purchasePrices
     * @return AbstractTextDocument[]
     */
    private function buildTextDocumentEntities(
        array $criteria,
        array $textDocumentData,
        array $authorEntities,
        array $purchasePrices
    ): array {
        $textDocumentEntities = [];

        $hasAuthorCriteria =
            count(
                array_filter(
                    array_keys($criteria),
                    static function (string $key) {
                        return 0 === strpos($key, 'author_');
                    }
                )
            ) > 0;

        foreach ($textDocumentData as $textDocumentItem) {
            if ($hasAuthorCriteria && false === array_key_exists($textDocumentItem['author_id'], $authorEntities)) {
                continue;
            }


            $textDocumentItem['author'] = null === $textDocumentItem['author_id'] ? null :
                $authorEntities[$textDocumentItem['author_id']];

            $yearDate = DateTimeImmutable::createFromFormat('Y-m-d', $textDocumentItem['year']);
            $textDocumentItem['year'] = (int)$yearDate->format('Y');

            $textDocumentItem['purchasePrices'] = false === array_key_exists($textDocumentItem['id'], $purchasePrices)
                ? []
                : $purchasePrices[$textDocumentItem['id']];

            if ('magazine' === $textDocumentItem['type']) {
                $textDocumentEntities[] = Magazine::createFromArray($textDocumentItem);
            } elseif ('book' === $textDocumentItem['type']) {
                $textDocumentEntities[] = Book::createFromArray($textDocumentItem);
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