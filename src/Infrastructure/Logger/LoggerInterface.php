<?php
namespace EfTech\BookLibrary\Infrastructure\Logger;

/** Интерфейс логирования
 *
 */
interface LoggerInterface
{
    /** Запись в логи сообщение
     *
     * @param string $msg - логируемое сообщение
     * @return void
     */
    public function log(string $msg): void;

}