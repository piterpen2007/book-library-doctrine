<?php

namespace EfTech\BookLibrary\ValueObject;

use EfTech\BookLibrary\Exception\RuntimeException;

/**
 *  Страна
 */
class Country
{
    /**
     * Уникальны код страны из двух символов. Символы это латинские буквы
     *
     * @var string
     */
    private string $code2;

    /**
     * Уникальны код страны из трех символов. Символы это латинские буквы
     *
     * @var string
     */
    private string $code3;

    /**
     * Уникальны код страны из двух символов. Символы это цифры
     *
     * @var string
     */
    private string $code;

    /**
     * Название страны
     *
     * @var string
     */
    private string $name;

    /**
     * @param string $code2 - Уникальны код страны из двух символов. Символы это латинские буквы
     * @param string $code3 - Уникальны код страны из трех символов. Символы это латинские буквы
     * @param string $code - Уникальны код страны из двух символов. Символы это цифры
     * @param string $name - Название страны
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
     * Валидация данных для создания ValueObject
     *
     * @param string $code2 - Уникальны код страны из двух символов. Символы это латинские буквы
     * @param string $code3 - Уникальны код страны из трех символов. Символы это латинские буквы
     * @param string $code - Уникальны код страны из двух символов. Символы это цифры
     * @param string $name - Название страны
     *
     * @return void
     */
    private function validate(string $code2, string $code3, string $code, string $name): void
    {
        if (1 !== preg_match('/^[A-Z]{2}$/', $code2)) {
            throw new RuntimeException('Некорректный двухбуквенный код страны');
        }

        if (1 !== preg_match('/^[A-Z]{3}$/', $code3)) {
            throw new RuntimeException('Некорректный трехбуквенный код страны');
        }

        if (1 !== preg_match('/^\d{3}$/', $code)) {
            throw new RuntimeException('Некорректный числовой код страны');
        }

        if (100 < strlen($name)) {
            throw new RuntimeException('Длина имени страны не может превышать 100 символов');
        }

        if ('' === trim($name)) {
            throw new RuntimeException('Имя страны не может быть пустой строкой');
        }
    }

    /**
     * Возвращает двухзначный код страны
     *
     * @return string
     */
    public function getCode2(): string
    {
        return $this->code2;
    }

    /**
     * Возвращает трехбуквенный код страны
     *
     * @return string
     */
    public function getCode3(): string
    {
        return $this->code3;
    }

    /**
     * Возвращает числовой код страны
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Возвращает имя страны
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Каст к string
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->code2;
    }


}