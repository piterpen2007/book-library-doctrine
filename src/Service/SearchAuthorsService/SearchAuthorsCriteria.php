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
     * @var string|null
     */
    private ?string $id;
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
     * @return string|null
     */
    public function getId(): ?string
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
     * @param string|null $id
     *
     * @return SearchAuthorsCriteria
     */
    public function setId(?string $id): SearchAuthorsCriteria
    {
        $this->id = $id;
        return $this;
    }
}
