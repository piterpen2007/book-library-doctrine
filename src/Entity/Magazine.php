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
     * @param array $purchasePrices
     * @param Status $status
     */
    public function __construct(
        int $id,
        string $title,
        DateTimeImmutable $year,
        array $authors,
        array $purchasePrices,
        Status $status,
        array $number
    ) {
        parent::__construct($id, $title, $year, $purchasePrices, $status, $authors);
        $this->number = $number;
    }

    /**
     * @return MagazineNumber[]
     */
    public function getNumbers(): array
    {
        return $this->number;
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
