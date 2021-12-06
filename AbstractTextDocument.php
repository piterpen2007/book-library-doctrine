<?php

abstract class AbstractTextDocument implements JsonSerializable
{
    /**
     * @var int id книги
     */
    private int $id;
    /**
     * @var string Заголовок книги
     */
    private string $title;
    /**
     * @var int Год выпуска книги
     */
    private int $year;

    /** Устанавливает id текстового документа
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id):self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /** Устанавливает id текстового документа
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return AbstractTextDocument
     */
    public function setTitle(string $title): AbstractTextDocument
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return int
     */
    public function getYear(): int
    {
        return $this->year;
    }

    /**
     * @param int $year
     * @return AbstractTextDocument
     */
    public function setYear(int $year): AbstractTextDocument
    {
        $this->year = $year;
        return $this;
    }

    /** Возвращает заголовок для печати
     * @return string
     */
    abstract public function getTitleForPrinting():string;

    public function jsonSerialize():array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'year' => $this->year,
            'title_for_printing' => $this->getTitleForPrinting()
        ];
    }
}