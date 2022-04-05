<?php

namespace EfTech\BookLibrary\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use EfTech\BookLibrary\Entity\TextDocument\Status;
use EfTech\BookLibrary\Exception;

/**
 * Журнал
 *
 * @ORM\Entity
 * @ORM\Table(
 *     name="text_document_magazines",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(name="issn_uniq", columns={"issn"})
 *     }
 * )
 *
 */
class Magazine extends AbstractTextDocument
{
    /**
     * Международный стандартный номер сериальных изданий
     *
     * @ORM\Column(name="issn", type="string", length=8, nullable=true, unique=true)
     *
     * @var string|null
     */
    private ?string $issn;

    /**
     * Номера журналов
     *
     * @ORM\OneToMany(targetEntity=\EFTech\BookLibrary\Entity\MagazineNumber::class, mappedBy="magazine")
     *
     * @var Collection|MagazineNumber[]
     */
    private Collection $numbers;

    /**
     * @param int $id                 - id журнала
     * @param string $title           - название журнала
     * @param DateTimeImmutable $year - год издания журнала
     * @param array $authors
     * @param array $purchasePrices   - закупочная цена
     * @param Status $status
     * @param string|null $isbn       - Международный стандартный номер сериальных изданий
     * @param array $numbers
     */
    public function __construct(
        int $id,
        string $title,
        DateTimeImmutable $year,
        array $authors,
        array $purchasePrices,
        Status $status,
        string $isbn = null,
        array $numbers = []
    ) {
        parent::__construct($id, $title, $year, $purchasePrices, $status, $authors);
        $this->issn = $isbn;

        foreach ($numbers as $magazineNumber) {
            if (false === ($magazineNumber instanceof MagazineNumber)) {
                throw new Exception\RuntimeException('Данные о номере журнала представлены некорректно');
            }
        }
        $this->numbers = new ArrayCollection($numbers);
    }

    /**
     * @return Collection|MagazineNumber[]
     */
    public function getNumbers()
    {
        return $this->numbers->toArray();
    }

    /**
     * Международный стандартный номер сериальных изданий
     *
     * @return string|null
     */
    public function getIssn(): ?string
    {
        return $this->issn;
    }

    /**
     * @inheritDoc
     */
    public function getTitleForPrinting(): string
    {
        return "{$this->getTitle()}. {$this->getYear()->format('Y')}.";
    }

}
