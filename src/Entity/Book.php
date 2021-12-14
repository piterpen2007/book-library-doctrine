<?php

namespace EfTech\BookLibrary\Entity;
use EfTech\BookLibrary\Infrastructure\invalidDataStructureException;

final class Book extends AbstractTextDocument
{
    /**
     * @var Author данные о авторе
     */
    private Author $author;

    /**
     * @param int $id
     * @param string $title
     * @param int $year
     * @param Author $author
     */
    public function __construct(int $id, string $title, int $year, Author $author)
    {
        parent::__construct($id, $title, $year);
        $this->author = $author;
    }

    /** Возвращает автора книги
     * @return Author
     */
    public function getAuthor(): Author
    {
        return $this->author;
    }


    /**
     * Устанавливает автора книги
     * @param Author $author
     * @return Book
     */
    public function setAuthor(Author $author): Book
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
        return "{$this->getTitle()} . {$this->getAuthor()->getSurname()} {$this->getAuthor()->getName()} . {$this->getYear()}";
    }

    public function jsonSerialize(): array
    {
        $jsonData = parent::jsonSerialize();
        $jsonData['author'] = $this->author;
        return $jsonData;
    }

    public static function createFromArray(array $data): Book
    {
        $requiredFields = [
            'id',
            'title',
            'year',
            'author'
        ];


        $missingFields = array_diff($requiredFields, array_keys($data));

        if (count($missingFields) > 0) {
            $errMsg = sprintf('Отсутствуют обязательные элементы: %s', implode(',', $missingFields));
            throw new invalidDataStructureException($errMsg);
        }


        return new Book($data['id'], $data['title'], $data['year'], $data['author']);
    }
}