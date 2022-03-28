<?php

namespace EfTech\BookLibrary\Entity\TextDocument;

use Doctrine\ORM\Mapping as ORM;
use EfTech\BookLibrary\Exception;

/**
 * Статус
 *
 * @ORM\Entity
 * @ORM\Table(
 *     name="text_document_status",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(name="text_document_status_name_unq",columns={"name"})
 *     }
 * )
 */
class Status
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="authors_id_seq")
     * @var int id статуса
     */
    private int $id = -1;
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
     * @ORM\Column(name="name", type="string", length=50)
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
