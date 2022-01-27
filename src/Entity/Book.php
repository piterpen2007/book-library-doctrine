<?php

namespace EfTech\BookLibrary\Entity;

use EfTech\BookLibrary\Exception;

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
     * @param array $purchasePrices
     * @param string $status
     */
    public function __construct(
        int $id,
        string $title,
        int $year,
        Author $author,
        array $purchasePrices,
        string $status
    ) {
        parent::__construct($id, $title, $year, $purchasePrices, $status);
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
        return "{$this->getTitle()} ." .
            " {$this->getAuthor()->getSurname()} {$this->getAuthor()->getName()} . {$this->getYear()}";
    }


    public static function createFromArray(array $data): Book
    {
        $requiredFields = [
            'id',
            'title',
            'year',
            'author',
            'purchasePrices',
            'status'
        ];


        $missingFields = array_diff($requiredFields, array_keys($data));

        if (count($missingFields) > 0) {
            $errMsg = sprintf('Отсутствуют обязательные элементы: %s', implode(',', $missingFields));
            throw new Exception\InvalidDataStructureException($errMsg);
        }


        return new Book(
            $data['id'],
            $data['title'],
            $data['year'],
            $data['author'],
            $data['purchasePrices'],
            $data['status']
        );
    }
}
