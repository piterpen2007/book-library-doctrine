<?php

namespace EfTech\BookLibrary\Entity\TextDocument;

use EfTech\BookLibrary\Exception;

/**
 * Статус
 */
final class Status
{
    /**
     * Статус в наличии
     */
    public const STATUS_IN_STOCK = 'inStock';

    /**
     * Статус в архиве
     */
    public const STATUS_ARCHIVE = 'archive';

    /**
     * Допустимые статусы
     */
    private const ALLOWED_STATUS = [
        self::STATUS_IN_STOCK => self::STATUS_IN_STOCK,
        self::STATUS_ARCHIVE  => self::STATUS_ARCHIVE,
    ];

    /**
     * Статус
     *
     * @var string
     */
    private string $name;

    /**
     * @param string $name - Название статуса
     */
    public function __construct(string $name)
    {
        $this->validate($name);
        $this->name = $name;
    }

    /**
     * Валидация статуса
     *
     * @param string $name - Название статуса
     *
     * @return void
     */
    private function validate(string $name): void
    {
        if (false === array_key_exists($name, self::ALLOWED_STATUS)) {
            throw new Exception\RuntimeException('Некорректный статус текстового документа');
        }
    }

    /**
     * Возвращает наименование статуса
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
        return $this->name;
    }



}
