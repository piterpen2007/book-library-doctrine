<?php

namespace EfTech\BookLibrary\Entity\TextDocument;

use EfTech\BookLibrary\Exception\RuntimeException;

class Status
{
    /**
     * Статус в наличие
     */
    public const STATUS_IN_STOCK = 'inStock';

    /**
     * Статус в архиве
     */
    public const STATUS_ARCHIVE = 'archive';

    private const ALLOWED_STATUS = [
        self::STATUS_IN_STOCK => self::STATUS_IN_STOCK,
        self::STATUS_ARCHIVE => self::STATUS_ARCHIVE
    ];


    /**
     * Статус
     *
     * @var string
     */
    private string $name;

    /**
     * @param string $name
     * @param string $code
     */
    public function __construct(string $name, string $code)
    {
        if (1 !== preg_match('/[A-Z]{3}/', $code)) {
            $this->validate($name);
        }
        $this->name = $name;

    }

    /**
     * Валидация статуса
     *
     * @param string $name
     */
    private function validate(string $name)
    {
        if (false === array_key_exists($name, self::ALLOWED_STATUS)) {
            throw new RuntimeException('Некорректный статус текстового документа: ' . $name);
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
