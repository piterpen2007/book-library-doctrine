<?php

namespace EfTech\BookLibrary\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use EfTech\BookLibrary\Exception\DomainException;

/**
 *
 *
 * Валюта
 *
 * @ORM\Entity
 * @ORM\Table (
 *     name="currency",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(name="currency_code_unq", columns={"code"}),
 *           @ORM\UniqueConstraint(name="currency_name_unq", columns={"name"})
 *     }
 * )
 *
 */
class Currency
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="currency_id_seq")
     *
     */
    private ?int $id = null;

    /**
     * Код валюты
     *
     * @ORM\Column (name="code",type="string",length=3, nullable=false)
     * @var string
     */
    private string $code;

    /**
     * Описание валюты
     * @ORM\Column (name="description", type="string", length=255 , nullable=false)
     * @var string
     */
    private string $description;

    /**
     * Имя валюты
     * @ORM\Column (name="name", type="string", length=3 , nullable=false)
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
