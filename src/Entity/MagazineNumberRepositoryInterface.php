<?php

namespace EfTech\BookLibrary\Entity;

/**
 *  Интерфейс репозитория номера журнала
 *
 */
interface MagazineNumberRepositoryInterface
{
    /**
     * Поиск сущностей по заданному критерию
     *
     * @param array $criteria - критерии поиска
     * @return MagazineNumber[]
     */
    public function findBy(array $criteria): array;


    /**
     *  Возващает id для создания следующего номера журнала
     *
     * @return int
     */
    public function nextId(): int;
    /**
     * Добавляет новую сущность
     *
     * @param MagazineNumber $entity
     * @return MagazineNumber
     */
    public function add(MagazineNumber $entity): MagazineNumber;
}