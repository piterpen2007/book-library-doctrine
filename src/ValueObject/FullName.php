<?php

namespace EfTech\BookLibrary\ValueObject;

use Doctrine\ORM\Mapping as ORM;

/**
 * Полное имя пользователя
 *
 * @ORM\Embeddable()
 */
class FullName
{
    /**
     * Имя
     *
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     */
    private string $name;

    /**
     * Фамилия
     *
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=50, nullable=false)
     */
    private string $surname;

    /**
     * @param string $name    - Имя
     * @param string $surname - Фамилия
     */
    public function __construct(string $name, string $surname)
    {
        $this->name = $name;
        $this->surname = $surname;
    }

    /**
     * Возвращает имя пользователя
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Возвращает фамилию пользователя
     *
     * @return string
     */
    public function getSurname(): string
    {
        return $this->surname;
    }
}
