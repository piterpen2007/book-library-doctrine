<?php

namespace EfTech\BookLibrary\Exception;

use EfTech\BookLibrary\Infrastructure\Exception as BaseException;

/**
 * Исключение выбрасывается в случае, если данные с которыми работает приложение имеют не валидную структуру
 */
class InvalidDataStructureException extends BaseException\InvalidDataStructureException implements ExceptionInterface
{
}
