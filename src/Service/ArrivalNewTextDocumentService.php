<?php

namespace EfTech\BookLibrary\Service;

use DateTimeImmutable;
use EfTech\BookLibrary\Entity\Author;
use EfTech\BookLibrary\Entity\AuthorRepositoryInterface;
use EfTech\BookLibrary\Entity\Book;
use EfTech\BookLibrary\Entity\Magazine;
use EfTech\BookLibrary\Entity\MagazineNumber;
use EfTech\BookLibrary\Entity\MagazineNumberRepositoryInterface;
use EfTech\BookLibrary\Entity\TextDocument\Status;
use EfTech\BookLibrary\Entity\TextDocumentRepositoryInterface;
use EfTech\BookLibrary\Service\ArchiveTextDocumentService\Exception\RuntimeException;
use EfTech\BookLibrary\Service\ArrivalNewTextDocumentService\NewBookDto;
use EfTech\BookLibrary\Service\ArrivalNewTextDocumentService\NewMagazineDto;
use EfTech\BookLibrary\Service\ArrivalNewTextDocumentService\ResultRegisteringTextDocumentDto;

/**
 * Сервис регистрации книг/журналов
 */
final class ArrivalNewTextDocumentService
{
    /**
     * Репозиторий для работы с текстовыми документами
     *
     * @var TextDocumentRepositoryInterface
     */
    private TextDocumentRepositoryInterface $textDocumentRepository;

    /**
     * Репозиторий для работы с авторами
     *
     * @var AuthorRepositoryInterface
     */
    private AuthorRepositoryInterface $authorRepository;

    /**
     * Репозиторий для работы с номерами журналов
     *
     * @var MagazineNumberRepositoryInterface
     */
    private MagazineNumberRepositoryInterface $magazineNumberRepository;

    /**
     * @param TextDocumentRepositoryInterface $textDocumentRepository - Репозиторий для работы с текстовыми документами
     * @param AuthorRepositoryInterface $authorRepository - Репозиторий для работы с авторами
     * @param MagazineNumberRepositoryInterface $magazineNumberRepository Репозиторий для работы с номерами журналов
     */
    public function __construct(
        TextDocumentRepositoryInterface $textDocumentRepository,
        AuthorRepositoryInterface $authorRepository,
        \EfTech\BookLibrary\Entity\MagazineNumberRepositoryInterface $magazineNumberRepository
    ) {
        $this->textDocumentRepository = $textDocumentRepository;
        $this->authorRepository = $authorRepository;
        $this->magazineNumberRepository = $magazineNumberRepository;
    }

    /**
     * Загружает сущности авторов по их идентификаторам
     *
     * @param array $authorIdList
     *
     * @return array
     */
    private function loadAuthorEntities(array $authorIdList): array
    {
        if (0 === count($authorIdList)) {
            return [];
        }

        $authorsCollection = $this->authorRepository->findBy(['id' => $authorIdList]);

        if (count($authorsCollection) !== count($authorIdList)) {
            $actualCurrentIdList = array_map(static function (Author $a) {
                return $a->getId();
            }, $authorsCollection);
            $unFoundId = implode(', ', array_diff($authorIdList, $actualCurrentIdList));
            $errMsg = "Нельзя зарегистрировать текстовой документ с author_id = '$unFoundId'";
            throw new RuntimeException($errMsg);
        }
        return $authorsCollection;
    }

    /**
     * Регистрация новой книги
     *
     * @param NewBookDto $bookDto
     *
     * @return ResultRegisteringTextDocumentDto
     */
    public function registerBook(NewBookDto $bookDto): ResultRegisteringTextDocumentDto
    {
        $entity = new Book(
            $this->textDocumentRepository->nextId(),
            $bookDto->getTitle(),
            DateTimeImmutable::createFromFormat('Y', $bookDto->getYear()),
            $this->loadAuthorEntities($bookDto->getAuthorIds()),
            [],
            new Status(Status::STATUS_IN_STOCK)
        );
        $this->textDocumentRepository->add($entity);

        return new ResultRegisteringTextDocumentDto(
            $entity->getId(),
            $entity->getTitleForPrinting(),
            $entity->getStatus()->getName()
        );
    }

    /**
     * Получает журнал на основе данных из дто
     *
     * @param NewMagazineDto $dto
     * @return Magazine
     */
    private function getMagazineEntity(NewMagazineDto $dto): Magazine
    {
        $magazines = $this->textDocumentRepository->findBy(['title' => $dto->getTitle()]);
        $countMagazines = count($magazines);

        if (0 === $countMagazines) {
            $magazine = new Magazine(
                $this->textDocumentRepository->nextId(),
                $dto->getTitle(),
                DateTimeImmutable::createFromFormat('Y', $dto->getYear()),
                $this->loadAuthorEntities($dto->getAuthorIds()),
                //$magazineDto->getNumber(),
                [],
                new Status(Status::STATUS_IN_STOCK)
            );
        } elseif (1 === $countMagazines) {
            $magazine = current($magazines);
        } else {
            throw new \EfTech\BookLibrary\Exception\RuntimeException(
                "Найдено $countMagazines журналов с именем {$dto->getTitle()}"
            );
        }
        return $magazine;
    }

    /**
     * Регистрация нового журнала
     *
     * @param NewMagazineDto $magazineDto
     *
     * @return ResultRegisteringTextDocumentDto
     */
    public function registerMagazine(NewMagazineDto $magazineDto): ResultRegisteringTextDocumentDto
    {
        $magazine = $this->getMagazineEntity($magazineDto);

        $existMagazineNumbers = $this->magazineNumberRepository
            ->findBy(['magazine' => $magazine, 'number' => $magazineDto->getNumber()]);

        $countExistsMagazineNumbers = count($existMagazineNumbers);

        if (0 === $countExistsMagazineNumbers) {
            $magazineNumber = new MagazineNumber(
                $this->magazineNumberRepository->nextId(),
                $magazine,
                $magazineDto->getNumber()
            );
            $magazine->getNumbers()->add($magazineNumber);
            $this->magazineNumberRepository->add($magazineNumber);
        } elseif (1 === $countExistsMagazineNumbers) {
            $magazineNumber = current($existMagazineNumbers);
        } else {
            throw new \EfTech\BookLibrary\Exception\RuntimeException(
                "Найденно более одного номера журнала с названием "
                . "{$magazineDto->getTitle()} и номером {$magazineDto->getNumber()}"
            );
        }


        return new ResultRegisteringTextDocumentDto(
            $magazineNumber->getId(),
            $magazineNumber->getTitleForPrinting(),
            $magazineNumber->getStatus()->getName()
        );
    }
}
