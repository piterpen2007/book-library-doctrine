<?php

namespace EfTech\BookLibrary\Entity;

interface AuthorRepositoryInterface
{
    /** Поиск сущностей по заданному критерию
     *
     * @param array $criteria
     * @return Author[]
     */
    public function findBy(array $criteria): array;
}
