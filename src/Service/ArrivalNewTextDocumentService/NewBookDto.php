<?php

namespace EfTech\BookLibrary\Service\ArrivalNewTextDocumentService;

final class NewBookDto
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
     * @var int
     */
    private int $authorId;

    /**
     * @param string $title Заголовок книги
     * @param int $year Год выпуска книги
     * @param int $authorId id автора книги
     */
    public function __construct(string $title, int $year, int $authorId)
    {
        $this->title = $title;
        $this->year = $year;
        $this->authorId = $authorId;
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
     * @return int
     */
    public function getAuthorId(): int
    {
        return $this->authorId;
    }




}