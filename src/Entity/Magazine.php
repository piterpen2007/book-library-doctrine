<?php

namespace EfTech\BookLibrary\Entity;


use EfTech\BookLibrary\Infrastructure\invalidDataStructureException;

require_once __DIR__ . '/Author.php';
require_once __DIR__ . '/AbstractTextDocument.php';
require_once __DIR__ . '/../Infrastructure/invalidDataStructureException.php';

final class Magazine extends AbstractTextDocument
{
    /**
     * @var int Номер журнала
     */
    private int $number;
    /**
     * @var ?Author данные о авторе
     */
    private ?Author $author;

    /**
     * @param int $id
     * @param string $title
     * @param int $year
     * @param Author|null $author
     * @param int $number
     */
    public function __construct(int $id, string $title, int $year, ?Author $author, int $number)
    {
        parent::__construct($id, $title, $year);
        $this->number = $number;
        $this->author = $author;
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @param int $number
     * @return Magazine
     */
    public function setNumber(int $number): Magazine
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return Author|null
     */
    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    /**
     * @param Author|null $author
     * @return Magazine
     */
    public function setAuthor(?Author $author): Magazine
    {
        $this->author = $author;
        return $this;
    }

    /** Выводит заголовок для печати
     *
     * @return string
     */
    public function getTitleForPrinting(): string
    {
        return "{$this->getTitle()} . {$this->getYear()} . Номер:  {$this->getNumber()}";
    }

    public function jsonSerialize(): array
    {
        $jsonData = parent::jsonSerialize();
        $jsonData['author'] = $this->author;
        $jsonData['number'] = $this->number;
        return $jsonData;
    }

    public static function createFromArray(array $data): Magazine
    {
        $requiredFields = [
            'id',
            'title',
            'year',
            'number',
            'author'
        ];

        $missingFields = array_diff($requiredFields, array_keys($data));

        if (count($missingFields) > 0) {
            $errMsg = sprintf('Отсутствуют обязательные элементы: %s', implode(',', $missingFields));
            throw new invalidDataStructureException($errMsg);
        }

        return new Magazine($data['id'], $data['title'], $data['year'], $data['author'], $data['number']);
    }


}