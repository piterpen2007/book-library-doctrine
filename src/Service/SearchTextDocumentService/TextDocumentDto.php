<?php

namespace EfTech\BookLibrary\Service\SearchTextDocumentService;

/** Структура информации о печатных изданиях
 *
 */
final class TextDocumentDto
{
    /**
     * -
     */
    public const TYPE_BOOK = 'book';
    /**
     * -
     */
    public const TYPE_MAGAZINE = 'magazine';
    /**
     *
     *
     * @var string
     */
    private string $type;
    /**
     * id
     *
     * @var int
     */
    private int $id;
    /**
     *
     *
     * @var string
     */
    private string $title;
    /**
     *
     *
     * @var int
     */
    private int $year;
    /**
     *
     *
     * @var AuthorDto|null
     */
    private ?AuthorDto $author;
    /**
     *
     *
     * @var int|null
     */
    private ?int $number;
    /**
     *
     *
     * @var string
     */
    private string $titleForPrinting;
    /**
     *
     * @param string $type
     * @param int $id
     * @param string $title
     * @param string $titleForPrinting
     * @param int $year
     * @param AuthorDto|null $author
     * @param int|null $number
     */
    public function __construct(
        string $type,
        int $id,
        string $title,
        string $titleForPrinting,
        int $year,
        ?AuthorDto $author,
        ?int $number
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->year = $year;
        $this->type = $type;
        $this->author = $author;
        $this->number = $number;
        $this->titleForPrinting = $titleForPrinting;
    }
    /**
     * id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    /**
     *
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
    /**
     *
     *
     * @return int
     */
    public function getYear(): int
    {
        return $this->year;
    }
    /**
     *
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
    /**
     *
     *
     * @return AuthorDto|null
     */
    public function getAuthor(): ?AuthorDto
    {
        return $this->author;
    }
    /**
     * ,
     *
     * @return int|null
     */
    public function getNumber(): int
    {
        return $this->number;
    }
    /**
     *
     *
     * @return string
     */
    public function getTitleForPrinting(): string
    {
        return $this->titleForPrinting;
    }
}
