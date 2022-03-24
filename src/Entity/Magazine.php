<?php

namespace EfTech\BookLibrary\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use EfTech\BookLibrary\Entity\TextDocument\Status;
use EfTech\BookLibrary\Exception;

/**
 * @ORM\Entity
 * @ORM\Table(name="text_document_magazines")
 *
 * Магазин
 */
class Magazine extends AbstractTextDocument
{
    /**
     *
     *
     * @var int Номер журнала
     * @ORM\Column(type="integer", name="number", nullable=false)
     */
    private int $number;

    /**
     * @param int $id
     * @param string $title
     * @param DateTimeImmutable $year
     * @param array $authors
     * @param int $number
     * @param array $purchasePrices
     * @param Status $status
     */
    public function __construct(
        int $id,
        string $title,
        DateTimeImmutable $year,
        array $authors,
        int $number,
        array $purchasePrices,
        Status $status
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

    /** Выводит заголовок для печати
     *
     * @return string
     */
    public function getTitleForPrinting(): string
    {
        return "{$this->getTitle()} . {$this->getYear()->format('Y')} . Номер:  {$this->getNumber()}";
    }

}
