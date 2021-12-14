<?php
namespace EfTech\BookLibrary\Controller;

use EfTech\BookLibrary\Entity\Author;
use EfTech\BookLibrary\Infrastructure\AppConfig;
use EfTech\BookLibrary\Infrastructure\Logger\LoggerInterface;
use function EfTech\BookLibrary\Infrastructure\loadData;
use function EfTech\BookLibrary\Infrastructure\paramTypeValidation;

require_once __DIR__ . '/../Infrastructure/app.function.php';


/** Функция поиска авторов
 * @param $request array - параметры которые передаёт пользователь
 * @param $appConfig - конфиг приложения
 * @logger LoggerInterface - параметр инкапсулирующий логгирование
 * @return array - возвращает результат поиска по авторам
 */
return static function (array $request, LoggerInterface $logger, AppConfig $appConfig):array
{
    $authorsJson = loadData($appConfig->getPathToAuthor());
    $logger->log('dispatch "authors" url');

    $paramValidations = [
        'surname' => 'inccorrect surname author'
    ];

    if(null === ($result = paramTypeValidation($paramValidations, $request))) {
        $foundAuthor = [];
        foreach ($authorsJson as $currentAuthor) {
            if (array_key_exists('surname', $request) && $currentAuthor['surname'] === $request['surname']) {
                $foundAuthor[] = Author::createFromArray($currentAuthor);
            }
        }
        $logger->log('found authors: ' . count($foundAuthor));
        return [
            'httpCode' => 200,
            'result' => $foundAuthor
        ];
    }
    return $result;

};
