<?php

namespace EfTech\BookLibrary\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=\EFTech\BookLibrary\Repository\MagazineNumberDoctrineRepository::class)
 * @ORM\Table(
 *     name="text_document_magazine_numbers",
 *     indexes={
 *          @ORM\Index(name="magazine_number_idx", columns={"number"})
 *     }
 * )
 */
class MagazineNumber
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="text_document_magazine_numbers_id_seq")
     * @ORM\Column(type="integer", name="id")
     */
    private int $id;

    /**
     * Журнал
     *
     * @ORM\ManyToOne(
     *     targetEntity=\EFTech\BookLibrary\Entity\Magazine::class,
     *     inversedBy="numbers",
     *     cascade={"persist"}
     * )
     * @ORM\JoinColumn(name="magazine_id", referencedColumnName="id")
     *
     * @var Magazine
     */
    private Magazine $magazine;

    /**
     * Номер выпуска
     *
     * @ORM\Column(name="number", type="integer", nullable=false)
     *
     * @var int
     */
    private int $number;

    /**
     * @param int $id            - id номера журнала
     * @param Magazine $magazine - Журнал
     * @param int $number        - Номер выпуска
     */
    public function __construct(int $id, Magazine $magazine, int $number)
    {
        $this->id = $id;
        $this->magazine = $magazine;
        $this->number = $number;
    }

    /**
     * id номера журнала
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Журнал
     *
     * @return Magazine
     */
    public function getMagazine(): Magazine
    {
        return $this->magazine;
    }

    /**
     * Номер выпуска
     *
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @inheritDoc
     */
    public function getTitleForPrinting(): string
    {
        return "{$this->getMagazine()->getTitleForPrinting()} Номер: {$this->getNumber()}.";
    }

}
