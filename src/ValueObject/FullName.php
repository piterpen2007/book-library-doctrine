<?php

namespace EfTech\BookLibrary\ValueObject;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class FullName
{
    /**
     * Имя
     *
     * @ORM\Column(name="name",type="string", length=255, nullable=false)
     *
     * @var string
     */
    private string $name;
    /**
     * Фамилия
     *
     * @var string
     * @ORM\Column(name="surname",type="string", length=255, nullable=false)
     */
    private string $surname;

    /**
     * @param string $name
     * @param string $surname
     */
    public function __construct(string $name, string $surname)
    {
        $this->name = $name;
        $this->surname = $surname;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSurname(): string
    {
        return $this->surname;
    }


}