<?php

namespace EfTech\BookLibrary\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use EfTech\BookLibrary\ValueObject\Country;
use Doctrine\ORM\Mapping as ORM;
use EfTech\BookLibrary\ValueObject\FullName;

/**
 * Автор
 *
 * @ORM\Entity(repositoryClass=\EfTech\BookLibrary\Repository\AuthorDoctrineRepository::class)
 *
 * @ORM\Table (
 *     name="authors",
 *     indexes={
 *          @ORM\Index(name="authors_surname_idx", columns={"surname"})
 *     }
 * )
 *
 */

final class Author
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="authors_id_seq")
     * @var int id автора
     */
    private int $id;
    /**
     * @ORM\Column(name="birthday", type="date_immutable", nullable=false)
     *
     * @var DateTimeImmutable Дата рождения автора
     */
    private DateTimeImmutable $birthday;
    /**
     * @ORM\ManyToOne(targetEntity=\EfTech\BookLibrary\ValueObject\Country::class)
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     *
     * @var Country Страна рождения автора
     */
    private Country $country;

    /**
     * Текстовые документы созданные авторами
     * @ORM\ManyToMany(targetEntity=\EfTech\BookLibrary\Entity\AbstractTextDocument::class, inversedBy="authors")
     * @ORM\JoinTable(
     *     name="text_document_to_author",
     *     joinColumns={@ORM\JoinColumn(name="author_id",referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="text_document_id",referencedColumnName="id")}
     * )
     *
     * @var Collection|AbstractTextDocument[]
     */
    private Collection $textDocuments;

    /**
     *
     * @ORM\Embedded(class=\EfTech\BookLibrary\ValueObject\FullName::class, columnPrefix=false)
     * @var FullName
     */
    private FullName $fullName;

    /**
     * @param int $id
     * @param FullName $fullName
     * @param DateTimeImmutable $birthday
     * @param Country $country
     * @param array $textDocuments
     */
    public function __construct(
        int $id,
        FullName $fullName,
        DateTimeImmutable $birthday,
        Country $country,
        array $textDocuments = []
    )
    {
        $this->id = $id;
        $this->fullName = $fullName;
        $this->birthday = $birthday;
        $this->country = $country;
        $this->textDocuments = new ArrayCollection($textDocuments);
    }

    /**
     * @return array
     */
    public function getTextDocuments(): array
    {
        return $this->textDocuments->toArray();
    }


    /**
     * @return FullName
     */
    public function getFullName(): FullName
    {
        return $this->fullName;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Author
     */
    public function setId(int $id): Author
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getBirthday(): DateTimeImmutable
    {
        return $this->birthday;
    }

    /**
     * @param DateTimeImmutable $birthday
     * @return Author
     */
    public function setBirthday(DateTimeImmutable $birthday): Author
    {
        $this->birthday = $birthday;
        return $this;
    }

    /**
     * @return Country
     */
    public function getCountry(): Country
    {
        return $this->country;
    }

    /**
     * @param Country $country
     * @return Author
     */
    public function setCountry(Country $country): Author
    {
        $this->country = $country;
        return $this;
    }
}
