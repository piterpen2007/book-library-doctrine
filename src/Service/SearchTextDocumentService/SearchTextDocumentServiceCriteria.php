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
     * @var int|null
     */
    private ?int $authorId = null;
    /**
     * @var string|null
     */
    private ?string $authorCountry = null;
    /**
     * @var string|null
     */
    private ?string $authorBirthday = null;
    /**
     * @var string|null
     */
    private ?string $authorName = null;
    /**
     *
     *
     * @var string|null
     */
    private ?string $authorSurname = null;
    /**
     * id
     *
     * @var int|null
     */
    private ?int $id = null;
    /**
     *
     *
     * @var string|null
     */
    private ?string $title = null;
    /**
     * @var string|null
     */
    private ?string $year = null;
    /**
     * @var string|null
     */
    private ?string $type = null;
    /**
     * @var string|null
     */
    private ?string $status = null;
    /**
     *
     *
     * @param string|null $authorSurname
     *
     * @return SearchTextDocumentServiceCriteria
     */
    public function setAuthorSurname(?string $authorSurname): SearchTextDocumentServiceCriteria
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
    public function setId(?int $id): SearchTextDocumentServiceCriteria
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
    public function setTitle(?string $title): SearchTextDocumentServiceCriteria
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

    /**
     * @return int|null
     */
    public function getAuthorId(): ?int
    {
        return $this->authorId;
    }

    /**
     * @param int|null $authorId
     * @return SearchTextDocumentServiceCriteria
     */
    public function setAuthorId(?int $authorId): SearchTextDocumentServiceCriteria
    {
        $this->authorId = $authorId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAuthorCountry(): ?string
    {
        return $this->authorCountry;
    }

    /**
     * @param string|null $authorCountry
     * @return SearchTextDocumentServiceCriteria
     */
    public function setAuthorCountry(?string $authorCountry): SearchTextDocumentServiceCriteria
    {
        $this->authorCountry = $authorCountry;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAuthorBirthday(): ?string
    {
        return $this->authorBirthday;
    }

    /**
     * @param string|null $authorBirthday
     * @return SearchTextDocumentServiceCriteria
     */
    public function setAuthorBirthday(?string $authorBirthday): SearchTextDocumentServiceCriteria
    {
        $this->authorBirthday = $authorBirthday;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAuthorName(): ?string
    {
        return $this->authorName;
    }

    /**
     * @param string|null $authorName
     * @return SearchTextDocumentServiceCriteria
     */
    public function setAuthorName(?string $authorName): SearchTextDocumentServiceCriteria
    {
        $this->authorName = $authorName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getYear(): ?string
    {
        return $this->year;
    }

    /**
     * @param string|null $year
     * @return SearchTextDocumentServiceCriteria
     */
    public function setYear(?string $year): SearchTextDocumentServiceCriteria
    {
        $this->year = $year;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     * @return SearchTextDocumentServiceCriteria
     */
    public function setType(?string $type): SearchTextDocumentServiceCriteria
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string|null $status
     * @return SearchTextDocumentServiceCriteria
     */
    public function setStatus(?string $status): SearchTextDocumentServiceCriteria
    {
        $this->status = $status;
        return $this;
    }

}
