<?php

namespace EfTech\BookLibrary\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Выпуск журнала
 * @ORM\Entity()
 * @ORM\Table(name="text_document_magazine_number", indexes={
 *      @ORM\Index(name="magazine_number_idx", columns={"number"})
 *     })
 */
class MagazineNumber
{
    /**
     * Идентификатор журнала
     *
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="text_document_magazine_number_id_seq")
     * @ORM\Column(name="id", type="integer")
     */
    private int $id;

    /**
     * К какому журналу принадлежит выпуск
     *
     * @var Magazine
     *
     * @ORM\ManyToOne(targetEntity=\EfTech\BookLibrary\Entity\Magazine::class, inversedBy="numbers",
     *     cascade={"persist"})
     * Какая колонка в текущей таблице будет ссылаться на какую-то колонку в другой таблице
     * @ORM\JoinColumn(name="magazine_id", referencedColumnName="id")
     */
    private Magazine $magazine;

    /**
     * Номер выпуска
     *
     * @var int
     *
     * @ORM\Column(name="number", type="integer", nullable=false)
     */
    private int $number;

    /**
     * @param int      $id       - Идентификатор выпуска
     * @param Magazine $magazine - Журнал
     * @param int      $number   - Номер выпуска
     */
    public function __construct(int $id, Magazine $magazine, int $number)
    {
        $this->id = $id;
        $this->magazine = $magazine;
        $this->number = $number;
    }

    /**
     * Возвращает идентификатор выпуска
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Возвращает журнал выпуска
     *
     * @return Magazine
     */
    public function getMagazine(): Magazine
    {
        return $this->magazine;
    }

    /**
     * Возвращает номер выпуска
     *
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }


    /**
     * Выводит заголовок для печати
     *
     * @return string
     */
    public function getTitleForPrinting(): string
    {
        return "Название: {$this->getMagazine()->getTitleForPrinting()}. Номер: {$this->getNumber()}.";
    }
}
