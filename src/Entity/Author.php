<?php

namespace EfTech\BookLibrary\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use EfTech\BookLibrary\ValueObject\Country;
use EfTech\BookLibrary\ValueObject\FullName;

/**
 * Автор
 *
 * @ORM\Table (
 *     name="authors",
 *     indexes={
 *          @ORM\Index(name="authors_surname_idx", columns={"surname"})
 *     }
 *     )
 * @ORM\Entity(repositoryClass=\EfTech\BookLibrary\Repository\AuthorDoctrineRepository::class)
 */
class Author
{
    /**
     * Идентификатор автора
     *
     * @var int
     *
     * @ORM\Id()
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="authors_id_seq")
     */
    private int $id;

    /**
     * Текстовые документы автора
     *
     * @var Collection
     *
     * @ORM\ManyToMany(
     *     targetEntity=\EfTech\BookLibrary\Entity\AbstractTextDocument::class,
     *     inversedBy="authors"
     * )
     * @ORM\JoinTable(
     *     name="text_document_to_author",
     *     joinColumns={
     *          @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     * },
     *     inverseJoinColumns={@ORM\JoinColumn(name="text_document_id", referencedColumnName="id")
     * })
     */
    private Collection $textDocuments;

    /**
     * Дата рождения автора
     *
     * @var DateTimeImmutable
     *
     * @ORM\Column(name="birthday", type="date_immutable", nullable=false)
     */
    private DateTimeImmutable $birthday;

    /**
     * Страна автора
     *
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity=\EfTech\BookLibrary\ValueObject\Country::class, fetch="EAGER")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     */
    private Country $country;

    /**
     * Полное имя автора
     *
     * @var FullName
     *
     * @ORM\Embedded(class=\EfTech\BookLibrary\ValueObject\FullName::class, columnPrefix=false)
     */
    private FullName $fullName;

    /**
     * @param int                    $id            - Идентификатор автора
     * @param FullName               $fullName      - Полное имя автора
     * @param DateTimeImmutable      $birthday      - Дата рождения автора
     * @param Country                $country       - Страна
     * @param AbstractTextDocument[] $textDocuments - Текстовые документы, созданные автором
     */
    public function __construct(
        int $id,
        FullName $fullName,
        DateTimeImmutable $birthday,
        Country $country,
        array $textDocuments = []
    ) {
        $this->id = $id;
        $this->fullName = $fullName;
        $this->birthday = $birthday;
        $this->country = $country;
        $this->textDocuments = new ArrayCollection($textDocuments);
    }

    /**
     * Получить id автора
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Возвращает полное имя автора
     *
     * @return FullName
     */
    public function getFullName(): FullName
    {
        return $this->fullName;
    }

    /**
     * Получить дату рождения
     *
     * @return DateTimeImmutable
     */
    public function getBirthday(): DateTimeImmutable
    {
        return $this->birthday;
    }

    /**
     * Получить страну автора
     *
     * @return Country
     */
    public function getCountry(): Country
    {
        return $this->country;
    }

    /**
     * Возвращает список текстовых документов, написанных автором
     *
     * @return array
     */
    public function getTextDocuments(): array
    {
        return $this->textDocuments->toArray();
    }
}
