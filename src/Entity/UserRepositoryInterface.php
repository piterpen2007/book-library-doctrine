<?php

namespace EfTech\BookLibrary\Entity;

use EfTech\BookLibrary\Repository\UserRepository\UserDataProvider;

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
     * @return UserDataProvider|null
     */
    public function findUserByLogin(string $login): ?UserDataProvider;
}
