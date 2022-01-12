<?php

namespace EfTech\BookLibrary\Service\SearchTextDocumentService;

/** Сервис логики поиска текстовых документов
 *
 *
 * @package EfTech\BookLibrary\Service\SearchBooksService
 */
final class SearchTextDocumentServiceCriteria
{
    /**
     *
     *
     * @var string|null
     */
    private ?string $authorSurname;
    /**
     * id
     *
     * @var int|null
     */
    private ?int $id;
    /**
     *
     *
     * @var string|null
     */
    private ?string $title;
    /**
     *
     *
     * @param string|null $authorSurname
     *
     * @return SearchTextDocumentServiceCriteria
     */
    public function setAuthorSurname(?string $authorSurname):
    SearchTextDocumentServiceCriteria
    {
        $this->authorSurname = $authorSurname;
        return $this;
    }
    /**
     * id
     *
     * @param int|null $id
     *
     * @return SearchTextDocumentServiceCriteria
     */
    public function setId(?int $id):
    SearchTextDocumentServiceCriteria
    {
        $this->id = $id;
        return $this;
    }
    /**
     *
     *
     * @param string|null $title
     *
     * @return SearchTextDocumentServiceCriteria
     */
    public function setTitle(?string $title):
    SearchTextDocumentServiceCriteria
    {
        $this->title = $title;
        return $this;
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
     * @return string|null
     */
    public function getAuthorSurname(): ?string
    {
        return $this->authorSurname;
    }
    /**
     *
     *
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }
}
