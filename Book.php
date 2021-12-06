<?php

require_once __DIR__ . '/Author.php';
require_once __DIR__ . '/AbstractTextDocument.php';

class Book extends AbstractTextDocument
{
    /**
     * @var Author данные о авторе
     */
    private Author $author;

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
    public function getTitleForPrinting():string
    {
        return "{$this->getTitle()} . {$this->getAuthor()->getSurname()} {$this->getAuthor()->getName()} . {$this->getYear()}";
    }
    public function jsonSerialize():array
    {
        $jsonData = parent::jsonSerialize();
        $jsonData['author'] = $this->author;
        return $jsonData;
    }
}