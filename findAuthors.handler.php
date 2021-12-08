<?php

require_once __DIR__ . '/app.function.php';
require_once __DIR__ . '/AppConfig.php';

/** Функция поиска авторов
 * @param $request array - параметры которые передаёт пользователь
 * @param $appConfig - конфиг приложения
 * @logger callable - параметр инкапсулирующий логгирование
 * @return array - возвращает результат поиска по авторам
 */
return static function (array $request, callable $logger, AppConfig $appConfig):array
{
    $authorsJson = loadData($appConfig->getPathToAuthor());
    $logger('dispatch "authors" url');

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
        $logger('found authors: ' . count($foundAuthor));
        return [
            'httpCode' => 200,
            'result' => $foundAuthor
        ];
    }
    return $result;

};
