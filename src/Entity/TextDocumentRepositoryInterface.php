<?php

namespace EfTech\BookLibrary\Entity;
/**
 *  Интерфейс репозитория текстовых документов
 */
interface TextDocumentRepositoryInterface
{
    /** Поиск сущностей по заданному критерию
     *
     * @param array $criteria
     * @return AbstractTextDocument[]
     */
    public function findBy(array $criteria):array;

    /** Сохранить сущность в репозитории
     * @param AbstractTextDocument $entity
     * @return AbstractTextDocument
     */
    public function save(AbstractTextDocument $entity):AbstractTextDocument;
    public function nextId():int;

    /** Добавляет новую сущность
     * @param AbstractTextDocument $entity
     * @return AbstractTextDocument
     */
    public function add(AbstractTextDocument $entity):AbstractTextDocument;
}