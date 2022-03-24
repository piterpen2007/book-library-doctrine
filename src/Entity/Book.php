<?php

namespace EfTech\BookLibrary\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use EfTech\BookLibrary\Entity\TextDocument\Status;
use EfTech\BookLibrary\Exception;

/**
 * Книги
 *
 * @ORM\Entity
 * @ORM\Table(
 *     name="text_document_books",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(name="text_document_books_isbn_unq", columns={"isbn"})
 *      }
 * )
 */
class Book extends AbstractTextDocument
{

    /**
     * Международный серийный книжный номер
     *
     * @var string|null
     * @ORM\Column(type="string",name="isbn", length=13, nullable=false)
     */
    private ?string $isbn;

    /**
     * @param int $id
     * @param string $title
     * @param DateTimeImmutable $year
     * @param Author[] $authors
     * @param array $purchasePrices
     * @param Status $status
     * @param string|null $isbn
     */
    public function __construct(
        int $id,
        string $title,
        DateTimeImmutable $year,
        array $authors,
        array $purchasePrices,
        Status $status,
        ?string $isbn = null
    ) {
        parent::__construct($id, $title, $year, $purchasePrices, $status, $authors);
        if (0 === count($authors)) {
            $errMsg = 'У книги должен быть хотя бы один автор';
            throw new Exception\RuntimeException($errMsg);
        }
        $this->isbn = $isbn;
    }

    /**
     * Международный серийный книжный номер
     *
     *
     * @return string|null
     */
    public function getIsbn(): ?string
    {
        return $this->isbn;
    }


    /** Выводит заголовок для печати
     *
     * @return string
     */
    public function getTitleForPrinting(): string
    {
        $titlesAuthors = [];
        foreach ($this->getAuthors() as $author) {
            $titlesAuthors[] = $author->getFullName()->getSurname() . ' ' . $author->getFullName()->getName();
        }
        $titlesAuthorsTxt = implode(', ', $titlesAuthors);
        return "{$this->getTitle()} ." . $titlesAuthorsTxt . " {$this->getYear()->format('Y')}";
    }

}
