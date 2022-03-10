<?php

namespace EfTech\BookLibrary\ValueObject;

use EfTech\BookLibrary\Exception\RuntimeException;

/**
 *  Страна
 */
class Country
{
    /**
     * Уникальный код страны из дввух символов
     *
     * @var string
     */
    private string $code2;

    /**
     * Уникальный код страны из трех символов
     *
     * @var string
     */
    private string $code3;

    /**
     * Уникальный код страны из трех символов(цифровой код)
     *
     * @var string
     */
    private string $code;


    /**
     * Имя страны
     *
     * @var string
     */
    private string $name;

    /**
     * @param string $code2
     * @param string $code3
     * @param string $code
     * @param string $name
     */
    public function __construct(string $code2, string $code3, string $code, string $name)
    {
        $this->validate($code2, $code3, $code, $name);
        $this->code2 = $code2;
        $this->code3 = $code3;
        $this->code = $code;
        $this->name = $name;
    }

    /**
     *  Валидация параметров
     * @param string $code2
     * @param string $code3
     * @param string $code
     * @param string $name
     */
    private function validate(string $code2, string $code3, string $code, string $name): void
    {
        if (1 !== preg_match('/^[a-z]{2}$/', $code2)) {
            throw new RuntimeException('Некорректный двухбуквенный код страны');
        }
        if (1 !== preg_match('/^[a-z]{3}$/', $code3)) {
            throw new RuntimeException('Некорректный трехбуквенный код страны');
        }
        if (1 !== preg_match('/^\d{3}$/', $code)) {
            throw new RuntimeException('Некорректный цифровой код страны');
        }

        if (strlen($name) > 100) {
            throw new RuntimeException('Длина имени страны больше допустимого');
        }

        if ('' === trim($name)) {
            throw new RuntimeException('Имя страны не может быть пустой строкой
            ');
        }
    }

    /**
     * @return string
     */
    public function getCode2(): string
    {
        return $this->code2;
    }

    /**
     * @return string
     */
    public function getCode3(): string
    {
        return $this->code3;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }



}
