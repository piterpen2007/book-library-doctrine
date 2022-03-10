<?php

namespace EfTech\BookLibrary\Entity;

use EfTech\BookLibrary\Entity\TextDocument\Status;
use EfTech\BookLibrary\Exception;

final class Book extends AbstractTextDocument
{
    /**
     * @param int $id
     * @param string $title
     * @param int $year
     * @param Author[] $authors
     * @param array $purchasePrices
     * @param string $status
     */
    public function __construct(
        int $id,
        string $title,
        int $year,
        array $authors,
        array $purchasePrices,
        Status $status
    ) {
        parent::__construct($id, $title, $year, $purchasePrices, $status, $authors);
        if (0 === count($authors)) {
            $errMsg = 'У книги должен быть хотя бы один автор';
            throw new Exception\RuntimeException($errMsg);
        }
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
        $titlesAuthors = [];
        foreach ($this->getAuthors() as $author) {
            $titlesAuthors[] = $author->getSurname() . ' ' . $author->getName();
        }
        $titlesAuthorsTxt = implode(', ', $titlesAuthors);
        return "{$this->getTitle()} ." . $titlesAuthorsTxt . " {$this->getYear()}";
    }


    public static function createFromArray(array $data): Book
    {
        $requiredFields = [
            'id',
            'title',
            'year',
            'authors',
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
            $data['authors'],
            $data['purchasePrices'],
            $data['status']
        );
    }
}
