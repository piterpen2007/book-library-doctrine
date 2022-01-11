<?php

namespace EfTech\BookLibrary\Service\SearchTextDocumentService;
use EfTech\BookLibrary\Entity\AbstractTextDocument;
use EfTech\BookLibrary\Entity\Author;
use EfTech\BookLibrary\Entity\Book;
use EfTech\BookLibrary\Entity\Magazine;
use EfTech\BookLibrary\Exception\InvalidDataStructureException;
use EfTech\BookLibrary\Exception\RuntimeException;
use EfTech\BookLibrary\Infrastructure\DataLoader\DataLoaderInterface;
use EfTech\BookLibrary\Infrastructure\Logger\LoggerInterface;
use EfTech\BookLibrary\ValueObject\Currency;
use EfTech\BookLibrary\ValueObject\Money;
use EfTech\BookLibrary\ValueObject\PurchasePrice;
use DateTimeImmutable;
use Exception;
use JsonException;

/**
 *
 *
 * @package EfTech\BookLibrary\Service
 */
class SearchTextDocumentService
{
    /**
     *
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;
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

    /**
     * @param LoggerInterface $logger
     * @param string $pathToBooks
     * @param string $pathToMagazines
     * @param string $pathToAuthors
     * @param DataLoaderInterface $dataLoader
     */
    public function __construct(
        LoggerInterface $logger,
        string $pathToBooks,
        string $pathToMagazines,
        string $pathToAuthors,
        DataLoaderInterface $dataLoader
    )
    {
        $this->logger = $logger;
        $this->pathToBooks = $pathToBooks;
        $this->pathToMagazines = $pathToMagazines;
        $this->pathToAuthors = $pathToAuthors;
        $this->dataLoader = $dataLoader;
    }


    /**
     *
     *
     * @return array
     * @throws JsonException
     */
    private function loadTextDocumentData():array
    {
        $books = $this->dataLoader->loadData($this->pathToBooks);
        $magazines = $this->dataLoader->loadData($this->pathToMagazines);
        return array_merge($books, $magazines);
    }

    /**
     *
     *
     * @return Author[]
     * @throws Exception
     */
    private function loadAuthorEntity():array
    {
        $authors = $this->dataLoader->loadData($this->pathToAuthors);
        $authorIdToInfo = [];
        foreach ($authors as $author) {
            $authorObj = Author::createFromArray($author);
            $authorIdToInfo[$authorObj->getId()] = $authorObj;
        }
        return $authorIdToInfo;
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
        $textDocument['author'] = null === $textDocument['author_id'] ? null : $authorIdToEntity[$textDocument['author_id']];
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
    private function createPurchasePrices(array $textDocument):array
    {
        if(false === array_key_exists('purchase_price',$textDocument)) {
            throw new InvalidDataStructureException('Нет данных о закупочной цене');
        }
        if(false === is_array($textDocument['purchase_price'])) {
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
    private function createPurchasePrice($purchasePriceData):PurchasePrice
    {
        if (false === is_array($purchasePriceData)) {
            throw new InvalidDataStructureException('Данные о закупочной цене имею не верный формат');
        }
        if (false === array_key_exists('date',$purchasePriceData)) {
            throw new InvalidDataStructureException('Отсутствуют данные о времени закупки');
        }
        if (false === is_string($purchasePriceData['date'])) {
            throw new InvalidDataStructureException('Данные о времени закупки имеют не верный формат');
        }
        if (false === array_key_exists('price',$purchasePriceData)) {
            throw new InvalidDataStructureException('Отсутствуют данные о цене закупки');
        }
        if (false === is_int($purchasePriceData['price'])) {
            throw new InvalidDataStructureException('Данные о цене закупки имеют не верный формат');
        }
        if (false === array_key_exists('currency',$purchasePriceData)) {
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
     *
     * @param SearchTextDocumentServiceCriteria $searchCriteria
     * @return array
     * @throws JsonException
     * @throws Exception
     */
    private function searchEntity(SearchTextDocumentServiceCriteria $searchCriteria):array
    {
        $textDocuments = $this->loadTextDocumentData();
        $authorIdToEntity = $this->loadAuthorEntity();
        $foundTextDocument = [];
        foreach ($textDocuments as $textDocument) {
            if (null !== $searchCriteria->getAuthorSurname()) {
                $bookMeetsSearchCriteria = null !== $textDocument['author_id']
                    && $searchCriteria->getAuthorSurname()
                    === $authorIdToEntity[$textDocument['author_id']]->getSurname();
            } else {
                $bookMeetsSearchCriteria = true;
            }
            if ($bookMeetsSearchCriteria && null !== $searchCriteria->getId()) {
                $bookMeetsSearchCriteria = $searchCriteria->getId() === (string)$textDocument['id'];
            }
            if ($bookMeetsSearchCriteria && null !== $searchCriteria->getTitle()) {
                $bookMeetsSearchCriteria = $searchCriteria->getTitle() === $textDocument['title'];
            }
            if ($bookMeetsSearchCriteria) {
                $foundTextDocument[] = $this->textDocumentFactory($textDocument, $authorIdToEntity);
            }
        }
        return $foundTextDocument;
    }
    /**
     * Возвращает тип текстового документа
     *
     * @param $textDocument AbstractTextDocument
     *
     * @return string
     */
    private function getTextDocumentType(AbstractTextDocument $textDocument):string
    {
        if ($textDocument instanceof Magazine) {
            $type = TextDocumentDto::TYPE_MAGAZINE;
        } elseif($textDocument instanceof Book) {
            $type = TextDocumentDto::TYPE_BOOK;
        } else {
            throw new RuntimeException(' ');
        }
        return $type;
    }

    /**
     * Создание dto
     *
     * @param AbstractTextDocument $textDocument
     *
     * @return TextDocumentDto
    SearchTextDocumentService\TextDocumentDto
     */
    private function createDto(AbstractTextDocument $textDocument): TextDocumentDto
    {
        $authorDto = null;
        if ($textDocument instanceof Book || ($textDocument instanceof Magazine && null !== $textDocument->getAuthor())) {
            $author = $textDocument->getAuthor();
            $authorDto = new
            AuthorDto(
                $author->getId(),
                $author->getName(),
                $author->getSurname(),
                $author->getBirthday(),
                $author->getCountry()
            );
        }
        return new
        TextDocumentDto(
            $this->getTextDocumentType($textDocument),
            $textDocument->getId(),
            $textDocument->getTitle(),
            $textDocument->getTitleForPrinting(),
            $textDocument->getYear(),
            $authorDto,
            $textDocument instanceof Magazine ? $textDocument->getNumber() : null
        );
    }

    /**
     *
     *
     * @param SearchTextDocumentServiceCriteria $searchCriteria
     * @return TextDocumentDto[]
     * @throws JsonException
     */
    public function search(SearchTextDocumentServiceCriteria $searchCriteria):array
    {
        $entitiesCollection = $this->searchEntity($searchCriteria);
        $dtoCollection = [];
        foreach ($entitiesCollection as $entity) {
            $dtoCollection[] = $this->createDto($entity);
        }
        $this->logger->log("Найдено книг: " . count($entitiesCollection));
        return $dtoCollection;
    }

}