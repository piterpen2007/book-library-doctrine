<?php

namespace EfTech\BookLibrary\Repository\UserRepository;

use EfTech\BookLibrary\Entity\User;
use EfTech\BookLibrary\Infrastructure\Auth\UserDataProviderInterface;

/**
 * Поставщик данных о логине\пароле пользователя
 */
class UserDataProvider extends User implements
    UserDataProviderInterface
{
}
