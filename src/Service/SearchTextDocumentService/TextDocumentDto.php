<?php

namespace EfTech\BookLibrary\Service\SearchTextDocumentService;

use EfTech\BookLibrary\Exception\RuntimeException;

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
     * @var AuthorDto[]
     */
    private array $authors;
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
     * @param AuthorDto[] $authors
     * @param int|null $number
     */
    public function __construct(
        string $type,
        int $id,
        string $title,
        string $titleForPrinting,
        int $year,
        array $authors,
        ?int $number
    ) {
        foreach ($authors as $author) {
            if (!($author instanceof AuthorDto)) {
                throw new RuntimeException('Не корректный формат данных dto автора');
            }
        }
        $this->id = $id;
        $this->title = $title;
        $this->year = $year;
        $this->type = $type;
        $this->authors = $authors;
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
     * @return AuthorDto[]
     */
    public function getAuthors(): array
    {
        return $this->authors;
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
