<?php

namespace EfTech\BookLibrary\Repository;

use DateTimeImmutable;
use EfTech\BookLibrary\Entity\AbstractTextDocument;
use EfTech\BookLibrary\Entity\Author;
use EfTech\BookLibrary\Entity\Book;
use EfTech\BookLibrary\Entity\Magazine;
use EfTech\BookLibrary\Entity\TextDocument\Status;
use EfTech\BookLibrary\Entity\TextDocumentRepositoryInterface;
use EfTech\BookLibrary\Exception\RuntimeException;
use EfTech\BookLibrary\Infrastructure\Db\ConnectionInterface;
use EfTech\BookLibrary\ValueObject\Currency;
use EfTech\BookLibrary\ValueObject\Money;
use EfTech\BookLibrary\ValueObject\PurchasePrice;

class TextDocumentDbRepository implements TextDocumentRepositoryInterface
{
    /**
     *  Ключом является имя статуса текстового документа
     *
     * @var array|null
     *
     */
    private ?array $textDocumentStatusMap = null;
    /**
     * Справочник валюты
     *
     * @var array|null
     */
    private ?array $currencyMap = null;

    /**
     * Возвращает справочник валюты
     *
     * @return array|null
     */
    private function getCurrencyMap(): ?array
    {
        if (null === $this->currencyMap) {
            $rows = $this->connection
                ->query('SELECT id, name FROM currency')
                ->fetchAll();

            $this->currencyMap = [];
            foreach ($rows as $row) {
                $this->currencyMap[$row['name']] = $row;
            }
        }
        return $this->currencyMap;
    }

    /**
     * @return array|null
     */
    private function getTextDocumentStatusMap(): ?array
    {
        if (null === $this->textDocumentStatusMap) {
            $rows = $this->connection
                ->query('SELECT id, name FROM text_document_status')
                ->fetchAll();

            $this->textDocumentStatusMap = [];
            foreach ($rows as $row) {
                $this->textDocumentStatusMap[$row['name']] = $row;
            }
        }
        return $this->textDocumentStatusMap;
    }

    /**
     * Возвращает данные о валюте с заднным именем
     *
     * @param string $name
     * @return array
     */
    private function getCurrency(string $name): array
    {
        $map = $this->getCurrencyMap();

        if (false === array_key_exists($name, $map)) {
            throw new RuntimeException('Нет валюты с именем ' . $name);
        }
        return $map[$name];
    }
    /** Возвращает данные о статусе с заданным именем
     * @param string $name
     * @return array
     */
    private function getTextDocumentStatus(string $name): array
    {
        $map = $this->getTextDocumentStatusMap();

        if (false === array_key_exists($name, $map)) {
            throw new RuntimeException('У текстового документа нет статуса с именем ' . $name);
        }
        return $map[$name];
    }


    private const SEARCH_CRITERIA = [
        'author_surname' => 'a.surname',
        'author_id' => 'a.id',
        'author_name' => 'a.name',
        'author_birthday' => 'a.birthday',
        'author_country' => 'cntr.code2 ',
        'id' => 't.id',
        'title' => 't.title',
        'year' => 't.year',
        'status' => 'tds.name',
        'number' => 't.number',
        'type' => 't.type',
    ];

    /**
     *  Базовый sql запрос для поиска текстовых документов
     */
    private const BASE_SEARCH_SQL = <<<EOF
select t.id        as id,
       t.title     as title,
       t.year      as year,
       tds.name    as status,
       t.number    as number,
       t.type      as type,
       a.id        as author_id,
       a.name      as author_name,
       a.surname   as author_surname,
       a.birthday  as author_birthday,
       cntr.code2  as author_country_code2,
       cntr.code3  as author_country_code3,
       cntr.code  as author_country_code,
       cntr.name  as author_country_name,
       pp.id       as purchase_price_id,
       pp.price    as purchase_price_price,
       crnc.name   as purchase_price_currency_name,
       crnc.code   as purchase_price_currency_code,
       pp.date     as purchase_price_date
from text_documents as t
         join text_document_status as tds on t.status_id = tds.id
         left join text_document_to_author as tdta on t.id = tdta.text_document_id
         left join authors a on a.id = tdta.author_id
         left join country as cntr on a.country_id = cntr.id
         left join purchase_price as pp on t.id = pp.text_document_id
         left join currency as crnc on pp.currency_id = crnc.id
EOF;


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


    public function findBy(array $criteria): array
    {
        $textDocumentData = $this->loadData($criteria);

        return $this->buildTextDocument($textDocumentData);
    }

    /**
     * Реализация логики создания сущности на основе данных из бд
     *
     * @param array $data
     * @return array
     */
    private function buildTextDocument(array $data): array
    {
        $textDocumentData = [];
        $authors = [];
        foreach ($data as $row) {
            if (false === array_key_exists($row['id'], $textDocumentData)) {
                $yearDate = DateTimeImmutable::createFromFormat('Y-m-d', $row['year']);

                $textDocumentData[$row['id']] = [
                    'id' => $row['id'],
                    'title' => $row['title'],
                    'year' => $yearDate->format('Y'),
                    'number' => $row['number'],
                    'status' => new Status($row['status']),
                    'authors' => [],
                    'purchasePrices' => [],
                    'type' => $row['type']
                ];
            }
            if (null !== $row['author_id']) {
                if (false === array_key_exists($row['author_id'], $authors)) {
                    $authors[$row['author_id']] = new Author(
                        $row['author_id'],
                        $row['author_name'],
                        $row['author_surname'],
                        DateTimeImmutable::createFromFormat('Y-m-d', $row['author_birthday']),
                        $row['author_country']
                    );
                }
                $textDocumentData[$row['id']]['authors'][$row['author_id']] = $authors[$row['author_id']];
            }
            if (
                null !== $row['purchase_price_id']
                &&
                false === array_key_exists($row['purchase_price_id'], $textDocumentData[$row['id']]['purchasePrices'])
            ) {
                $currencyName = 'RUB' === $row['purchase_price_currency'] ? 'рубль' : 'неизвестно';

                $obj = new PurchasePrice(
                    DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $row['purchase_price_date']),
                    new Money(
                        $row['purchase_price_price'],
                        new Currency(
                            $row['purchase_price_currency'],
                            $currencyName
                        )
                    )
                );
                $textDocumentData[$row['id']]['purchasePrices'][$row['purchase_price_id']] = $obj;
            }
        }

        $textDocumentEntities = [];
        foreach ($textDocumentData as $item) {
            if ('magazine' === $item['type']) {
                $textDocumentEntities[] = new Magazine(
                    $item['id'],
                    $item['title'],
                    $item['year'],
                    $item['authors'],
                    $item['number'],
                    $item['purchasePrices'],
                    $item['status']
                );
            } elseif ('book' === $item['type']) {
                $textDocumentEntities[] = new Book(
                    $item['id'],
                    $item['title'],
                    $item['year'],
                    $item['authors'],
                    $item['purchasePrices'],
                    $item['status']
                );
            }
        }
        return $textDocumentEntities;
    }

    /**
     * Логика построения sql запроса на основе критериев поиска и получения данных
     *
     * @param array $criteria
     * @return array
     */
    private function loadData(array $criteria): array
    {
        $sql = self::BASE_SEARCH_SQL;
        $whereParts = [];
        $params = [];
        $notSupportedSearchCriteria = [];
        foreach ($criteria as $criteriaName => $criteriaValue) {
            if (array_key_exists($criteriaName, self::SEARCH_CRITERIA)) {
                $sqlParts = self::SEARCH_CRITERIA[$criteriaName];
                $whereParts[] = "$sqlParts=:$criteriaName";
                $params[$criteriaName] = $criteriaValue;
            } else {
                $notSupportedSearchCriteria[] = $criteriaName;
            }
            if (count($notSupportedSearchCriteria) > 0) {
                $errMsg = 'Неподдерживаемые критерии поиска текстовых документов'
                    . implode(', ', $notSupportedSearchCriteria);
                throw new RuntimeException($errMsg);
            }
        }
        if (count($whereParts) > 0) {
            $sql .= ' WHERE ' . implode(' AND ', $whereParts);
        }
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);

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
    status_id = :statusId,
    number = :number,
    type = :type
WHERE id = :id
EOF;
        $values = [
            'id' => $entity->getId(),
            'title' => $entity->getTitle(),
            'year' => "{$entity->getYear()}/01/01",
            'statusId' => $this->getTextDocumentStatus($entity->getStatus())['id'],
            'type' => null,
            'number' => null
        ];

        if ($entity instanceof Book) {
            $values['type'] = 'book';
        } elseif ($entity instanceof Magazine) {
            $values['type'] = 'magazine';
            $values['number'] = $entity->getNumber();
        } else {
            throw new RuntimeException('Текстовой документ данного типа не может быть добавлен');
        }

        $this->connection->prepare($sql)->execute($values);

        $this->saveTextDocumentToAuthor($entity);

        $sql = <<<EOF
INSERT INTO purchase_price
        (date, price, currency_id, text_document_id)
VALUES (:date, :price, :currencyId, :textDocumentId)
EOF;

        $stmt = $this->connection->prepare($sql);

        foreach ($entity->getPurchasePrices() as $purchasePrice) {
            $values = [
                'date' => $purchasePrice->getDate()->format('Y-m-d H:i:s'),
                'price' => $purchasePrice->getMoney()->getAmount(),
                'currency_id' => $this->getCurrency($purchasePrice->getMoney()->getCurrency()->getCode()),
                'textDocumentId' => $entity->getId()
            ];
            $stmt->execute($values);
        }
        return $entity;
    }

    public function nextId(): int
    {
        $sql = <<<EOF
SELECT nextval('text_documents_id_seq') AS next_id
EOF;

        return (int)current($this->connection->query($sql)->fetchAll())['next_id'];
    }

    /**
     * @inheritDoc
     */
    public function add(AbstractTextDocument $entity): AbstractTextDocument
    {
        $sql = <<<EOF
INSERT INTO text_documents (id, title, year, status_id, type, number)
VALUES (
        :id, :title, :year, :statusId, :type, :number
)
EOF;
        $values = [
            'id' => $entity->getId(),
            'title' => $entity->getTitle(),
            'year' => "{$entity->getYear()}/01/01",
            'statusId' => $this->getTextDocumentStatus($entity->getStatus())['id'],
            'type' => null,
            'number' => null
        ];

        if ($entity instanceof Book) {
            $values['type'] = 'book';
        } elseif ($entity instanceof Magazine) {
            $values['type'] = 'magazine';
            $values['number'] = $entity->getNumber();
        } else {
            throw new RuntimeException('Текстовой документ данного типа не может быть добавлен');
        }
        $this->connection->prepare($sql)->execute($values);

        $this->saveTextDocumentToAuthor($entity);

        return $entity;
    }

    private function saveTextDocumentToAuthor(AbstractTextDocument $entity): void
    {
        $this->connection->prepare('DELETE FROM text_document_to_author WHERE text_document_id = :textDocumentId')
            ->execute(['textDocumentId' => $entity->getId()]);

        $insertParts = [];
        $insertParams = [];
        foreach ($entity->getAuthors() as $index => $author) {
            $insertParts[] = "(:textDocumentId_$index, :authorId_$index)";
            $insertParams["textDocumentId_$index"] = $entity->getId();
            $insertParams["authorId_$index"] = $author->getId();
        }
        if (count($insertParts) > 0) {
            $values = implode(', ', $insertParts);
            $sql = <<<EOF
INSERT INTO text_document_to_author (text_document_id, author_id) VALUES $values
EOF;
            $this->connection->prepare($sql)->execute($insertParams);
        }
    }
}
