<?php

namespace EfTech\BookLibrary\Entity;

use EfTech\BookLibrary\Exception;

final class Magazine extends AbstractTextDocument
{
    /**
     * @var int Номер журнала
     */
    private int $number;

    /**
     * @param int $id
     * @param string $title
     * @param int $year
     * @param array $authors
     * @param int $number
     * @param array $purchasePrices
     * @param string $status
     */
    public function __construct(
        int $id,
        string $title,
        int $year,
        array $authors,
        int $number,
        array $purchasePrices,
        string $status
    ) {
        parent::__construct($id, $title, $year, $purchasePrices, $status, $authors);
        $this->number = $number;
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


    public static function createFromArray(array $data): Magazine
    {
        $requiredFields = [
            'id',
            'title',
            'year',
            'number',
            'authors',
            'purchasePrices',
            'status'
        ];

        $missingFields = array_diff($requiredFields, array_keys($data));

        if (count($missingFields) > 0) {
            $errMsg = sprintf('Отсутствуют обязательные элементы: %s', implode(',', $missingFields));
            throw new Exception\invalidDataStructureException($errMsg);
        }

        return new Magazine(
            $data['id'],
            $data['title'],
            $data['year'],
            $data['authors'],
            $data['number'],
            $data['purchasePrices'],
            $data['status']
        );
    }
}
