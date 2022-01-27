<?php

namespace EfTech\BookLibrary\Service\ArrivalNewTextDocumentService;

/**
 * Результат регистрации текстового документа
 */
class ResultRegisteringTextDocumentDto
{
    /** id текстового документа
     * @var int
     */
    private int $id;
    /** Заголовок для печати текстового документа
     * @var string
     */
    private string $titleForPrinting;
    /** Статус текстового документа
     * @var string
     */
    private string $status;

    /**
     * @param int $id id текстового документа
     * @param string $titleForPrinting Заголовок для печати текстового документа
     * @param string $status Статус текстового документа
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
