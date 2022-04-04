<?php

namespace EfTech\BookLibrary\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use EfTech\BookLibrary\Entity\TextDocument\Status;
use EfTech\BookLibrary\Exception\DomainException;
use EfTech\BookLibrary\Exception\RuntimeException;
use EfTech\BookLibrary\ValueObject\PurchasePrice;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=\EfTech\BookLibrary\Repository\TextDocumentDoctrineRepository::class)
 * @ORM\Table(
 *     name="text_documents",
 *     indexes={
 *          @ORM\Index(name="text_documents_title_idx", columns={"title"}),
 *          @ORM\Index(name="text_documents_year_idx", columns={"year"}),
 *          @ORM\Index(name="text_documents_type_idx", columns={"type"})
 *     }
 * )
 *
 *  Текстовый документ
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type",type="string",length=30)
 * @ORM\DiscriminatorMap({
 *          "book" = \EfTech\BookLibrary\Entity\Book::class,
 *          "magazine" = \EfTech\BookLibrary\Entity\Magazine::class
 *     })
 *
 */
abstract class AbstractTextDocument
{
    /**
     * Автор текстового документа
     * @ORM\ManyToMany(targetEntity=\EfTech\BookLibrary\Entity\Author::class, mappedBy="textDocuments")
     *
     * @var Author[]|Collection
     */
    private Collection $authors;
    /**
     * @var int id книги
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="text_documents_id_seq")
     * @ORM\Column(type="integer",name="id",nullable=false)
     */
    private int $id;
    /**
     * @ORM\Column(type="string",name="title", length=255, nullable=false)
     * @var string Заголовок книги
     */
    private string $title;
    /**
     * @ORM\Column(type="date_immutable",name="year",nullable=false)
     *
     * @var DateTimeImmutable Год выпуска книги
     */
    private DateTimeImmutable $year;
    /**
     * Данные о закупочных ценах
     *
     * @var Collection
     *
     * @ORM\OneToMany(
     *     targetEntity=\EfTech\BookLibrary\ValueObject\PurchasePrice::class,
     *     mappedBy="textDocument"
     * )
     */
    private Collection $purchasePrices;



    /** Статус текстовго документа
     *
     *
     * @var Status
     * @ORM\ManyToOne(targetEntity=\EfTech\BookLibrary\Entity\TextDocument\Status::class, cascade={"persist"},fetch="EAGER")
     * @ORM\JoinColumn(name="status_id",referencedColumnName="id")
     *
     */
    private Status $status;

    /** Конструктор класса
     *
     * @param int $id - id книги
     * @param string $title - Заголовок книги
     * @param DateTimeImmutable $year - Год выпуска книги
     * @param array $purchasePrices
     * @param Status $status
     * @param Collection $authors
     */
    public function __construct(
        int $id,
        string $title,
        DateTimeImmutable $year,
        array $purchasePrices,
        Status $status,
        array $authors
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->year = $year;

        foreach ($purchasePrices as $purchasePrice) {
            if (!$purchasePrice instanceof PurchasePrice) {
                throw new DomainException('Некорректный формат данных по закупочной цене');
            }
        }
        $this->purchasePrices = new ArrayCollection($purchasePrices);
        $this->status = $status;
        foreach ($authors as $author) {
            if (!$author instanceof Author) {
                throw new DomainException('Сущность автора имеет неверный формат');
            }
        }
        $this->authors = new ArrayCollection($authors);
    }

    /** Перенос документа в архив
     * @return $this
     */
    public function moveToArchive(): self
    {
        if (Status::STATUS_ARCHIVE === $this->status->getName()) {
            throw new RuntimeException(
                "Текстовый документ с id {$this->getId()} уже находится в архиве"
            );
        }
        $this->status = new Status(Status::STATUS_ARCHIVE);
        return $this;
    }


    /** Возвращает данные о закупочных ценах
     * @return PurchasePrice[]
     */
    public function getPurchasePrices(): array
    {
        return $this->purchasePrices->toArray();
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
     *
     *
     * @return Author[]
     */
    public function getAuthors(): array
    {
        return $this->authors->toArray();
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
     * @return DateTimeImmutable
     */
    final public function getYear(): DateTimeImmutable
    {
        return $this->year;
    }

    /**
     * @param DateTimeImmutable $year
     * @return AbstractTextDocument
     */
    public function setYear(DateTimeImmutable $year): AbstractTextDocument
    {
        $this->year = $year;
        return $this;
    }

    /**
     * @param Status $status
     * @return AbstractTextDocument
     */
    public function setStatus(Status $status): AbstractTextDocument
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return Status
     */
    public function getStatus(): Status
    {
        return $this->status;
    }


    /** Возвращает заголовок для печати
     * @return string
     */
    abstract public function getTitleForPrinting(): string;

}
