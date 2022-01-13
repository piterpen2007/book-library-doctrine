<?php

namespace EfTech\BookLibrary\Service\ArrivalNewTextDocumentService;

/**
 * DTO - данные структуры нового журнала
 */
final class NewMagazineDto
{
    /**
     * @var string Заголовок книги
     */
    private string $title;
    /**
     * @var int Год выпуска книги
     */
    private int $year;
    /**
     * id автора книги
     *
     * @var int|null
     */
    private ?int $authorId = null;
    /** Номер журнала
     * @var int
     */
    private int $number;

    /**
     * @param string $title Заголовок книги
     * @param int $year Год выпуска книги
     * @param int|null $authorId id автора книги
     * @param int $number - номер журнала
     */
    public function __construct(string $title, int $year, ?int $authorId, int $number)
    {
        $this->title = $title;
        $this->year = $year;
        $this->authorId = $authorId;
        $this->number = $number;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return int
     */
    public function getYear(): int
    {
        return $this->year;
    }

    /**
     * @return int|null
     */
    public function getAuthorId(): ?int
    {
        return $this->authorId;
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }





}