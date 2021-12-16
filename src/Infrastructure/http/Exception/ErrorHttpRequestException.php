<?php
namespace EfTech\BookLibrary\Infrastructure\http\Exception;

use \EfTech\BookLibrary\Exception\RuntimeException;

/**
 *  Исключение выбрасывается в случае если не удалось создать объект http запроса
 */
class ErrorHttpRequestException extends RuntimeException
{

}