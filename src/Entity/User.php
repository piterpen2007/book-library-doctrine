<?php

namespace EfTech\BookLibrary\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Пользователь системы
 *
 * @ORM\MappedSuperclass()
 */
class User
{
    /** id пользователя
     *
     * @ORM\Id()
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @var int
     */
    private int $id;

    /** Логин пользователя в системе
     *
     * @ORM\Column(name="login",type="string", length=50, nullable=false)
     * @var string
     */
    private string $login;
    /** Пароль пользователя
     *
     * @ORM\Column(name="password",type="string", length=60, nullable=false)
     * @var string
     */
    private string $password;

    /**
     * @param int $id id пользователя
     * @param string $login Логин пользователя в системе
     * @param string $password Пароль пользователя
     */
    public function __construct(int $id, string $login, string $password)
    {
        $this->id = $id;
        $this->login = $login;
        $this->password = $password;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}
