<?php

namespace EfTech\BookLibrary\Service\SearchAuthorsService;

/** Класс декларирующий по каким критериям можно вести поиск по авторам
 *
 *
 * @package EfTech\BookLibrary\Service\SearchAuthors
 */
final class SearchAuthorsCriteria
{
    /**
     * @var string|null
     */
    private ?string $country = null;
    /**
     * @var string|null
     */
    private ?string $birthday = null;
    /**
     * @var string|null
     */
    private ?string $name = null;
    /**
     *
     *
     * @var string|null
     */
    private ?string $surname = null;
    /**
     * id
     *
     * @var int|null
     */
    private ?int $id = null;
    /**
     *
     *
     * @return string|null
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string|null $country
     * @return SearchAuthorsCriteria
     */
    public function setCountry(?string $country): SearchAuthorsCriteria
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBirthday(): ?string
    {
        return $this->birthday;
    }

    /**
     * @param string|null $birthday
     * @return SearchAuthorsCriteria
     */
    public function setBirthday(?string $birthday): SearchAuthorsCriteria
    {
        $this->birthday = $birthday;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return SearchAuthorsCriteria
     */
    public function setName(?string $name): SearchAuthorsCriteria
    {
        $this->name = $name;
        return $this;
    }
    /**
     * id
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }
    /**
     *
     *
     * @param string|null $surname
     *
     * @return SearchAuthorsCriteria
     */
    public function setSurname(?string $surname): SearchAuthorsCriteria
    {
        $this->surname = $surname;
        return $this;
    }
    /**
     * id
     *
     * @param int|null $id
     *
     * @return SearchAuthorsCriteria
     */
    public function setId(?int $id): SearchAuthorsCriteria
    {
        $this->id = $id;
        return $this;
    }
}
