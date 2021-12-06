<?php

require_once __DIR__ . '/app.function.php';

/** Функция поиска авторов
 * @param $request array - параметры которые передаёт пользователь
 * @logger callable - параметр инкапсулирующий логгирование
 * @return array - возвращает результат поиска по авторам
 */
return static function (array $request, callable $logger):array
{
    $authorsJson = loadData('authors');
    $logger('dispatch "authors" url');

    $paramValidations = [
        'surname' => 'inccorrect surname author'
    ];

    if(null === ($result = paramTypeValidation($paramValidations, $request))) {
        $foundAuthor = [];
        foreach ($authorsJson as $currentAuthor) {
            if (array_key_exists('surname', $request) && $currentAuthor['surname'] === $request['surname']) {

                $authorObj = new Author();
                $authorObj->setId($currentAuthor['id'])
                    ->setName($currentAuthor['name'])
                    ->setSurname($currentAuthor['surname'])
                    ->setBirthday($currentAuthor['birthday'])
                    ->setCountry($currentAuthor['country']);


                $foundAuthor[] = $authorObj;

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
