<?php

namespace EfTech\BookLibrary\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Класс, реализующий логику создания пользователя
 *
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User
{
    /**
     * Идентификатор пользователя
     *
     * @var int
     *
     * @ORM\Id()
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="user_id_seq")
     */
    private int $id;

    /**
     * Логин пользователя
     *
     * @var string
     *
     * @ORM\Column(name="login", type="string", nullable=false, length=50)
     */
    private string $login;

    /**
     * Пароль пользователя
     *
     * @var string
     *
     * @ORM\Column(name="password", type="string", nullable=false, length=100)
     */
    private string $password;

    /**
     * @param int    $id       - Идентификатор пользователя
     * @param string $login    - Логин пользователя
     * @param string $password - Пароль пользователя
     */
    public function __construct(int $id, string $login, string $password)
    {
        $this->id = $id;
        $this->login = $login;
        $this->password = $password;
    }

    /**
     * Возвращает идентификатор пользователя
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Возвращает логин пользователя
     *
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * Возвращает пароль пользователя
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}
