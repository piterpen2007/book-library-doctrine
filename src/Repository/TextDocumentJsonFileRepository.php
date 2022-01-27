<?php

namespace EfTech\BookLibrary\Repository;

use DateTimeImmutable;
use EfTech\BookLibrary\Entity\AbstractTextDocument;
use EfTech\BookLibrary\Entity\Author;
use EfTech\BookLibrary\Entity\Book;
use EfTech\BookLibrary\Entity\Magazine;
use EfTech\BookLibrary\Entity\TextDocumentRepositoryInterface;
use EfTech\BookLibrary\Exception\InvalidDataStructureException;
use EfTech\BookLibrary\Exception\RuntimeException;
use EfTech\BookLibrary\Infrastructure\DataLoader\DataLoaderInterface;
use EfTech\BookLibrary\ValueObject\Currency;
use EfTech\BookLibrary\ValueObject\Money;
use EfTech\BookLibrary\ValueObject\PurchasePrice;

class TextDocumentJsonFileRepository implements TextDocumentRepositoryInterface
{
    /** Текущее значение идентификатора текстового документа
     * @var int
     */
    private int $currentId;
    /**
     *
     *
     * @var string
     */
    private string $pathToBooks;
    /**
     *
     *
     * @var string
     */
    private string $pathToMagazines;
    /**
     *
     *
     * @var string
     */
    private string $pathToAuthors;
    /**
     *
     *
     * @var DataLoaderInterface
     */
    private DataLoaderInterface $dataLoader;
    /** данные о текстовых документах
     * @var array|null
     */
    private ?array $textDocumentData = null;
    private ?array $authorIdToInfo =  null;
    /** Данные о книгах
     * @var array|null
     */
    private ?array $booksData;
    private ?array $magazinesData;
    /** Сопоставляет id книги с номером элемента в $booksData
     * @var array|null
     */
    private ?array $bookIdToIndex = null;

    /** Сопоставляет id журнала с номером элемента в $magazinesData
     * @var array|null
     */
    private ?array $magazineIdToIndex = null;


    /**
     * @param string $pathToBooks
     * @param string $pathToMagazines
     * @param string $pathToAuthors
     * @param DataLoaderInterface $dataLoader
     */
    public function __construct(
        string $pathToBooks,
        string $pathToMagazines,
        string $pathToAuthors,
        DataLoaderInterface $dataLoader
    ) {
        $this->pathToBooks = $pathToBooks;
        $this->pathToMagazines = $pathToMagazines;
        $this->pathToAuthors = $pathToAuthors;
        $this->dataLoader = $dataLoader;
    }
    /**
     *
     *
     * @return Author[]
     */
    private function loadAuthorEntity(): array
    {
        if (null === $this->authorIdToInfo) {
            $authors = $this->dataLoader->loadData($this->pathToAuthors);
            $authorIdToInfo = [];
            foreach ($authors as $author) {
                $authorObj = Author::createFromArray($author);
                $authorIdToInfo[$authorObj->getId()] = $authorObj;
            }
            $this->authorIdToInfo = $authorIdToInfo;
        }
        return $this->authorIdToInfo;
    }

    /**
     * @return array
     */
    private function loadTextDocumentData(): array
    {
        if (null === $this->textDocumentData) {
            $this->booksData = $this->dataLoader->loadData($this->pathToBooks);
            $this->magazinesData = $this->dataLoader->loadData($this->pathToMagazines);

            $this->bookIdToIndex = array_combine(
                array_map(
                    [$this,'extractTextDocument'],
                    $this->booksData
                ),
                array_keys($this->booksData)
            );
            $this->magazineIdToIndex = array_combine(
                array_map(
                    [$this,'extractTextDocument'],
                    $this->magazinesData
                ),
                array_keys($this->magazinesData)
            );

            $this->textDocumentData = array_merge($this->booksData, $this->magazinesData);
            $this->currentId = max(
                array_map(
                    static function (array $v) {
                        return $v['id'];
                    },
                    $this->textDocumentData
                )
            );
        }
        return $this->textDocumentData;
    }

    private function extractTextDocument($v): int
    {
        if (false === is_array($v)) {
            throw new InvalidDataStructureException('Данные о текстовом документе должны быть массивом');
        }
        if (false === array_key_exists('id', $v)) {
            throw new InvalidDataStructureException('Нету id текстового документа');
        }
        if (false === is_int($v['id'])) {
            throw new InvalidDataStructureException('id текстового документа должен быть целым числом');
        }
        return $v['id'];
    }

    /**
     *
     *
     * @param array $textDocument - (/)
     * @param Author[] $authorIdToEntity - , id
     *
     * @return AbstractTextDocument
     */
    private function textDocumentFactory(array $textDocument, array $authorIdToEntity): AbstractTextDocument
    {
        $textDocument['author'] = null === $textDocument['author_id'] ?
            null : $authorIdToEntity[$textDocument['author_id']];
        $textDocument['purchasePrices'] = $this->createPurchasePrices($textDocument);
        if (array_key_exists('number', $textDocument)) {
            $textDocumentObj = Magazine::createFromArray($textDocument);
        } else {
            $textDocumentObj = Book::createFromArray($textDocument);
        }
        return $textDocumentObj;
    }


    /**
     * - " "
     *
     * @param array $textDocument
     *
     * @return PurchasePrice[]
     */
    private function createPurchasePrices(array $textDocument): array
    {
        if (false === array_key_exists('purchase_price', $textDocument)) {
            throw new InvalidDataStructureException('Нет данных о закупочной цене');
        }
        if (false === is_array($textDocument['purchase_price'])) {
            throw new InvalidDataStructureException('Данные о закупочных ценах имею не верный формат');
        }
        $purchasePrices = [];
        foreach ($textDocument['purchase_price'] as $purchasePriceData) {
            $purchasePrices[] = $this->createPurchasePrice($purchasePriceData);
        }
        return $purchasePrices;
    }


    /**

     * @param $purchasePriceData
     *
     * @return PurchasePrice
     */
    private function createPurchasePrice($purchasePriceData): PurchasePrice
    {
        if (false === is_array($purchasePriceData)) {
            throw new InvalidDataStructureException('Данные о закупочной цене имею не верный формат');
        }
        if (false === array_key_exists('date', $purchasePriceData)) {
            throw new InvalidDataStructureException('Отсутствуют данные о времени закупки');
        }
        if (false === is_string($purchasePriceData['date'])) {
            throw new InvalidDataStructureException('Данные о времени закупки имеют не верный формат');
        }
        if (false === array_key_exists('price', $purchasePriceData)) {
            throw new InvalidDataStructureException('Отсутствуют данные о цене закупки');
        }
        if (false === is_int($purchasePriceData['price'])) {
            throw new InvalidDataStructureException('Данные о цене закупки имеют не верный формат');
        }
        if (false === array_key_exists('currency', $purchasePriceData)) {
            throw new InvalidDataStructureException('Отсутствуют данные о валюте закупки');
        }
        if (false === is_string($purchasePriceData['currency'])) {
            throw new InvalidDataStructureException('Данные о валюте имеют не верный формат');
        }
        $currencyName = 'RUB' === $purchasePriceData['currency'] ? 'рубль' : 'неизвестно';
        return new PurchasePrice(
            DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $purchasePriceData['date']),
            new Money(
                $purchasePriceData['price'],
                new Currency($purchasePriceData
                ['currency'], $currencyName)
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function findBy(array $criteria): array
    {
        $textDocuments = $this->loadTextDocumentData();
        $authorIdToEntity = $this->loadAuthorEntity();
        $foundTextDocument = [];
        foreach ($textDocuments as $textDocument) {
            if (array_key_exists('author_surname', $criteria)) {
                $bookMeetsSearchCriteria = null !== $textDocument['author_id']
                    && $criteria['author_surname']
                    === $authorIdToEntity[$textDocument['author_id']]->getSurname();
            } else {
                $bookMeetsSearchCriteria = true;
            }
            if ($bookMeetsSearchCriteria && array_key_exists('id', $criteria)) {
                $bookMeetsSearchCriteria = $criteria['id'] === $textDocument['id'];
            }
            if ($bookMeetsSearchCriteria && array_key_exists('title', $criteria)) {
                $bookMeetsSearchCriteria = $criteria['title'] === $textDocument['title'];
            }
            if ($bookMeetsSearchCriteria) {
                $foundTextDocument[] = $this->textDocumentFactory($textDocument, $authorIdToEntity);
            }
        }
        return $foundTextDocument;
    }

    /**
     * @inheritDoc
     */
    public function save(AbstractTextDocument $entity): AbstractTextDocument
    {
        $this->loadTextDocumentData();
        if ($entity instanceof Book) {
            $data = $this->booksData;
            $itemIndex = $this->getItemIndex($entity);
            $item = $this->buildJsonDataForBook($entity);
            $data[$itemIndex] = $item;
            $file = $this->pathToBooks;
        } elseif ($entity instanceof Magazine) {
            $data = $this->magazinesData;
            $itemIndex = $this->getItemIndex($entity);
            $item = $this->buildJsonDataForMagazine($entity);
            $data[$itemIndex] = $item;
            $file = $this->pathToMagazines;
        } else {
            throw new RuntimeException('Текстовый документ данного типа не может быть сохранен');
        }

        $jsonStr = json_encode($data, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($file, $jsonStr);
        return $entity;
    }

    /** Получение индекса элемента с данными для книги и журнала на основе id сущности
     * @param AbstractTextDocument $entity
     * @return int
     */
    private function getItemIndex(AbstractTextDocument $entity): int
    {
        $id = $entity->getId();
        if ($entity instanceof Book) {
            $entityToIndex = $this->bookIdToIndex;
        } elseif ($entity instanceof Magazine) {
            $entityToIndex = $this->magazineIdToIndex;
        } else {
            throw new RuntimeException('Для текстового документа заданного типа невозможно получить данные');
        }

        if (false === array_key_exists($id, $entityToIndex)) {
            throw new RuntimeException("Текстовой документ с id '$id' не найден в хранилище");
        }
        return $entityToIndex[$id];
    }

    /** Логика сериализации данных книг
     * @param Book $entity
     * @return array
     */
    private function buildJsonDataForBook(Book $entity): array
    {
        return [
                'id' => $entity->getId(),
                'title' => $entity->getTitle(),
                'year' => $entity->getYear(),
                'author_id' => $entity->getAuthor()->getId(),
                'status' => $entity->getStatus(),
                'purchase_price' => $this->buildJsonDataForPurchasePrices($entity)

        ];
    }

    /** Логика сериализации данных о закупочных ценнах
     * @param Book $entity
     * @return array
     */
    private function buildJsonDataForPurchasePrices(AbstractTextDocument $entity): array
    {
        $data = [];
        foreach ($entity->getPurchasePrices() as $purchasePrice) {
            $data[] = [
              'date' => $purchasePrice->getDate()->format('Y-m-d H:i:s'),
              'price' => $purchasePrice->getMoney()->getAmount(),
              'currency' => $purchasePrice->getMoney()->getCurrency()->getCode()
            ];
        }

        return $data;
    }

    /**
     * @param Magazine $entity
     * @return array
     */
    private function buildJsonDataForMagazine(Magazine $entity): array
    {
        $author = $entity->getAuthor();

        return [
            'id' => $entity->getId(),
            'title' => $entity->getTitle(),
            'year' => $entity->getYear(),
            'author_id' => null === $author ? null : $author->getId(),
            'status' => $entity->getStatus(),
            'number' => $entity->getNumber(),
            'purchase_price' => $this->buildJsonDataForPurchasePrices($entity)

        ];
    }

    public function nextId(): int
    {
        $this->loadTextDocumentData();
        ++$this->currentId;

        return $this->currentId;
    }

    public function add(AbstractTextDocument $entity): AbstractTextDocument
    {
        $this->loadTextDocumentData();
        if ($entity instanceof Book) {
            $item = $this->buildJsonDataForBook($entity);
            $this->booksData[] = $item;
            $data = $this->booksData;
            $this->bookIdToIndex[$entity->getId()] = array_key_last($this->booksData);
            $file = $this->pathToBooks;
        } elseif ($entity instanceof Magazine) {
            $item = $this->buildJsonDataForMagazine($entity);
            $this->magazinesData[] = $item;
            $data = $this->magazinesData;
            $this->magazineIdToIndex[$entity->getId()] = array_key_last($this->magazinesData);
            $file = $this->pathToMagazines;
        } else {
            throw new RuntimeException('Текстовой документ данного типа не может быть добавлен');
        }
        $this->textDocumentData[] = $item;
        $jsonStr = json_encode($data, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($file, $jsonStr);
        return $entity;
    }
}
