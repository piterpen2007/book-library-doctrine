<?php

namespace EfTech\BookLibrary\Service\ArchiveTextDocumentService;

/**
 *  Результат архивации документа
 */
final class ArchivingResultDto
{
    /** id текстового документа
     * @var int
     */
    private int $id;
    /**  Заголовок для печати
     * @var string
     */
    private string $titleForPrinting;
    /** Статус книги
     * @var string
     */
    private string $status;

    /**
     * @param int $id
     * @param string $titleForPrinting
     * @param string $status
     */
    public function __construct(int $id, string $titleForPrinting, string $status)
    {
        $this->id = $id;
        $this->titleForPrinting = $titleForPrinting;
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitleForPrinting(): string
    {
        return $this->titleForPrinting;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }
}
