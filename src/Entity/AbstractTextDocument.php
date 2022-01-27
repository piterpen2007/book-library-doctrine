<?php

namespace EfTech\BookLibrary\Entity;

use EfTech\BookLibrary\Exception\DomainException;
use EfTech\BookLibrary\Exception\RuntimeException;
use EfTech\BookLibrary\ValueObject\PurchasePrice;

abstract class AbstractTextDocument
{
    public const STATUS_ARCHIVE = 'archive';
    public const STATUS_IN_STOCK = 'inStock';
    /**
     * @var int id книги
     */
    private int $id;
    /**
     * @var string Заголовок книги
     */
    private string $title;
    /**
     * @var int Год выпуска книги
     */
    private int $year;
    /** Данные о закупочных ценах
     * @var PurchasePrice[]
     */
    private array $purchasePrices;
    /** Статус текстовго документа
     * @var string
     */
    private string $status;

    /** Конструктор класса
     *
     * @param int $id - id книги
     * @param string $title - Заголовок книги
     * @param int $year - Год выпуска книги
     * @param array $purchasePrices
     * @param string $status
     */
    public function __construct(int $id, string $title, int $year, array $purchasePrices, string $status)
    {
        $this->id = $id;
        $this->title = $title;
        $this->year = $year;

        foreach ($purchasePrices as $purchasePrice) {
            if (!$purchasePrice instanceof PurchasePrice) {
                throw new DomainException('Некорректный формат данных по закупочной цене');
            }
        }
        $this->purchasePrices = $purchasePrices;
        $this->status = $status;
    }

    /** Перенос документа в архив
     * @return $this
     */
    public function moveToArchive(): self
    {
        if ('archive' === $this->status) {
            throw new RuntimeException(
                "Текстовый документ с id {$this->getId()} уже находится в архиве"
            );
        }
        $this->status = self::STATUS_ARCHIVE;
        return $this;
    }


    /** Возвращает данные о закупочных ценах
     * @return PurchasePrice[]
     */
    public function getPurchasePrices(): array
    {
        return $this->purchasePrices;
    }



    /** Устанавливает id текстового документа
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    final public function getId(): int
    {
        return $this->id;
    }

    /** Устанавливает id текстового документа
     *
     * @return string
     */
    final public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return AbstractTextDocument
     */
    public function setTitle(string $title): AbstractTextDocument
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return int
     */
    final public function getYear(): int
    {
        return $this->year;
    }

    /**
     * @param int $year
     * @return AbstractTextDocument
     */
    public function setYear(int $year): AbstractTextDocument
    {
        $this->year = $year;
        return $this;
    }

    /**
     * @param string $status
     * @return AbstractTextDocument
     */
    public function setStatus(string $status): AbstractTextDocument
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }



    /** Возвращает заголовок для печати
     * @return string
     */
    abstract public function getTitleForPrinting(): string;


    abstract public static function createFromArray(array $data): AbstractTextDocument;
}
