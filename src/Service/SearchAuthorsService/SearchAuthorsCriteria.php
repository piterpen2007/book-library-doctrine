<?php
namespace EfTech\BookLibrary\Service\SearchAuthorsService;

/** Класс декларирующий по каким критериям можно вести поиск по авторам
 *
 *
 * @package EfTech\BookLibrary\Service\SearchAuthors
 */
final class SearchAuthorsCriteria
{
    /**
     *
     *
     * @var string|null
     */
    private ?string $surname;
    /**
     * id
     *
     * @var int|null
     */
    private ?int $id;
    /**
     *
     *
     * @return string|null
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }
    /**
     * id
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }
    /**
     *
     *
     * @param string|null $surname
     *
     * @return SearchAuthorsCriteria
     */
    public function setSurname(?string $surname): SearchAuthorsCriteria
    {
        $this->surname = $surname;
        return $this;
    }
    /**
     * id
     *
     * @param int|null $id
     *
     * @return SearchAuthorsCriteria
     */
    public function setId(?int $id): SearchAuthorsCriteria
    {
        $this->id = $id;
        return $this;
    }
}
