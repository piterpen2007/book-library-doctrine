<?php

namespace EfTech\BookLibrary\Entity;

/**
 * Интерфейс репозитория для сущности юзер
 */
interface UserRepositoryInterface
{
    /** Поиск сущностей по заданному критерию
     *
     * @param array $criteria
     * @return AbstractTextDocument[]
     */
    public function findBy(array $criteria): array;

    /** Поиск пользователя по логину
     * @param string $login
     * @return User|null
     */
    public function findUserByLogin(string $login): ?User;
}
