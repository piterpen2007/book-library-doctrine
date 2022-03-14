<?php

namespace EfTech\BookLibrary\ValueObject;

use EfTech\BookLibrary\Exception\DomainException;

/**
 * Валюта
 */
final class Currency
{
    /**
     * Код валюты
     *
     * @var string
     */
    private string $code;

    /**
     * Описание валюты
     *
     * @var string
     */
    private string $description;

    /**
     * Имя валюты
     *
     * @var string
     */
    private string $name;

    /**
     * @param string $code        - Код валюты
     * @param string $name        - Имя валюты
     * @param string $description - Описание валюты
     */
    public function __construct(string $code, string $name, string $description)
    {
        $this->validate($code, $name, $description);
        $this->description = $description;
        $this->code = $code;
        $this->name = $name;
    }

    /**
     * Валидация параметров для создания Currency
     *
     * @param string $code        - Код валюты
     * @param string $name        - Имя валюты
     * @param string $description - Описание валюты
     *
     * @return void
     */
    private function validate(string $code, string $name, string $description): void
    {
        if (1 !== preg_match('/^\d{3}$/', $code)) {
            throw new DomainException('Некорректный формат кода валюты');
        }

        if (1 !== preg_match('/^[A-Z]{3}$/', $name)) {
            throw new DomainException('Некорректное имя валюты');
        }

        if (255 < strlen($description)) {
            throw new DomainException('Длина описания валюты не может содержать больше 255 символов');
        }

        if ('' === trim($description)) {
            throw new DomainException('Описание валюты не может быть пустой');
        }
    }

    /**
     * Возвращает код валюты
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Возвращает имя валюты
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Возвращает описание валюты
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

}
