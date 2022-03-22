<?php

namespace EfTech\BookLibrary\Entity;

use DateTimeImmutable;
use EfTech\BookLibrary\Exception;
use EfTech\BookLibrary\ValueObject\Country;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity(repositoryClass=\EfTech\BookLibrary\Repository\AuthorDoctrineRepository::class)
 * @ORM\Table(name="authors")
 *
 * Автор
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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     *
     * @var string Имя автора
     */
    private string $name;
    /**
     * @ORM\Column(name="surname", type="string", length=255, nullable=false)
     *
     * @var string Фамилия автора
     */
    private string $surname;
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
     * @param int $id
     * @param string $name
     * @param string $surname
     * @param DateTimeImmutable $birthday
     * @param Country $country
     */
    public function __construct(int $id, string $name, string $surname, DateTimeImmutable $birthday, Country $country)
    {
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->birthday = $birthday;
        $this->country = $country;
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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Author
     */
    public function setName(string $name): Author
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     * @return Author
     */
    public function setSurname(string $surname): Author
    {
        $this->surname = $surname;
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


    /**
     * @param array $data
     * @return Author
     */
    public static function createFromArray(array $data): Author
    {
        $requiredFields = [
            'id',
            'name',
            'surname',
            'birthday',
            'country'
        ];

        $missingFields = array_diff($requiredFields, array_keys($data));

        if (count($missingFields) > 0) {
            $errMsg = sprintf('Отсутствуют обязательные элементы: %s', implode(',', $missingFields));
            throw new Exception\invalidDataStructureException($errMsg);
        }
        return new Author($data['id'], $data['name'], $data['surname'], $data['birthday'], $data['country']);
    }
}
