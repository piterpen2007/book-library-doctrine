<?php

namespace EfTech\BookLibrary\Service\ArrivalNewTextDocumentService;

use EfTech\BookLibrary\Exception\RuntimeException;

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
     * @var array
     */
    private array $authorIds;
    /** Номер журнала
     * @var int
     */
    private int $number;

    /**
     * @param string $title Заголовок книги
     * @param int $year Год выпуска книги
     * @param array $authorIds
     * @param int $number - номер журнала
     */
    public function __construct(string $title, int $year, array $authorIds, int $number)
    {
        foreach ($authorIds as $authorId) {
            if (false === is_int($authorId)) {
                throw new RuntimeException('id автора должен быть числом');
            }
        }
        $this->title = $title;
        $this->year = $year;
        $this->authorIds = $authorIds;
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
     * @return array
     */
    public function getAuthorIds(): array
    {
        return $this->authorIds;
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }
}
