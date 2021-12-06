<?php

/**
 * Автор
 */
class Author implements JsonSerializable
{
    /**
    * @var int id автора
    */
    private int $id;
     /**
     * @var string Имя автора
     */
    private string $name;
    /**
     * @var string Фамилия автора
     */
    private string $surname;
    /**
     * @var string Дата рождения автора
     */
    private string $birthday;
    /**
     * @var string Страна рождения автора
     */
    private string $country;

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
     * @return string
     */
    public function getBirthday(): string
    {
        return $this->birthday;
    }

    /**
     * @param string $birthday
     * @return Author
     */
    public function setBirthday(string $birthday): Author
    {
        $this->birthday = $birthday;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return Author
     */
    public function setCountry(string $country): Author
    {
        $this->country = $country;
        return $this;
    }
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name'=> $this->name,
            'surname' => $this->surname,
            'birthday' => $this->birthday,
            'country' => $this->country
        ];
    }

}