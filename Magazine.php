<?php

require_once __DIR__ . '/Author.php';
require_once __DIR__ . '/AbstractTextDocument.php';
class Magazine extends AbstractTextDocument {
    /**
     * @var int Номер журнала
     */
    private int $number;
    /**
     * @var ?Author данные о авторе
     */
    private ?Author $author;

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
    public function getTitleForPrinting():string
    {
        return "{$this->getTitle()} . {$this->getYear()} . Номер:  {$this->getNumber()}";
    }
    public function jsonSerialize():array
    {
        $jsonData = parent::jsonSerialize();
        $jsonData['author'] = $this->author;
        $jsonData['number'] = $this->number;
        return $jsonData;
    }


}