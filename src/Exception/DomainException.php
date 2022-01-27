<?php

namespace EfTech\BookLibrary\Exception;

use EfTech\BookLibrary\Infrastructure\Exception as BaseException;

/**
 * Выбрасывает исключение, если значеине ге соответствует определенной допустимой области данных
 */
class DomainException extends BaseException\DomainException implements ExceptionInterface
{
}
