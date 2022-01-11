<?php
namespace EfTech\BookLibrary\Service\SearchTextDocumentService;

/** Структура информации о авторах
 *
 */
final class AuthorDto
{
    /**
     * id
     *
     * @var int
     */
    private int $id;
    /**
     *
     *
     * @var string
     */
    private string $name;
    /**
     *
     *
     * @var string
     */
    private string $surname;
    /**
     *
     *
     * @var string
     */
    private string $birthday;
    /**
     *
     *
     * @var string
     */
    private string $country;
    /**
     * @param int $id - id автора
     * @param string $name - имя автора
     * @param string $surname - фамилия автора
     * @param string $birthday - день рождение автора
     * @param string $country - страна автора
     */
    public function __construct(int $id, string $name, string
    $surname, string $birthday, string $country)
    {
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->birthday = $birthday;
        $this->country = $country;
    }
    /**
     * id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    /**
     *
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    /**
     *
     *
     * @return string
     */
    public function getSurname(): string
    {
        return $this->surname;
    }
    /**
     *
     *
     * @return string
     */
    public function getBirthday(): string
    {
        return $this->birthday;
    }
    /**
     *
     *
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }
}
