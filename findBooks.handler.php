<?php

require_once __DIR__ . '/app.function.php';
require_once __DIR__ . '/Magazine.php';
require_once __DIR__ . '/Author.php';
require_once __DIR__ . '/Book.php';

/** Функция поиска книги
* @param $request array - параметры которые передаёт пользователь
* @logger callable - параметр инкапсулирующий логгирование
* @return array - возвращает результат поиска по книгам
*/
return static function (array $request, callable $logger):array
{
    $authorsJson = loadData('authors');
    $booksJson   = loadData('books');
    $magazinesJson = loadData('magazines');

    $logger('dispatch "books" url');

    $paramValidations = [
        'author_surname' => 'inccorrect author surname',
        'title' =>'inccorrect title book'
    ];

    $booksJson = array_merge($booksJson,$magazinesJson);

    if(null === ($result = paramTypeValidation($paramValidations, $request))) {
        $foundBooks = [];
        $authorIdToInfo = [];
        foreach ($authorsJson as $info) {
            $authorObj = new Author();
            $authorObj->setId($info['id'])
            ->setName($info['name'])
            ->setSurname($info['surname'])
            ->setBirthday($info['birthday'])
            ->setCountry($info['country']);

            $authorIdToInfo[$info['id']] = $authorObj;
        }

        foreach ($booksJson as $book) {
            if (array_key_exists('author_surname', $request)) {
                $bookMeetSearchCriteria = null !== $book['author_id'] && $request['author_surname'] === $authorIdToInfo[$book['author_id']]->getSurname();
            } else {
                $bookMeetSearchCriteria = true;
            }

            if ($bookMeetSearchCriteria && array_key_exists('title', $request)) {
                $bookMeetSearchCriteria = $request['title'] === $book['title'];
            }

            if ($bookMeetSearchCriteria) {
                if (array_key_exists('number',$book)) {
                    $bookObj = new Magazine();
                    $bookObj->setNumber($book['number']);

                } else {
                    $bookObj = new Book();
                }
                $bookObj->setId($book['id'])
                    ->setTitle($book['title'])
                    ->setYear($book['year'])
                    ->setAuthor(null === $book['author_id'] ? null : $authorIdToInfo[$book['author_id']]);

                //$book['author'] = $authorIdToInfo[$book['author_id']];
                //unset($book['author_id']);
                $foundBooks[] = $bookObj;
            }
        }
        $logger('found books ' . count($foundBooks));
        return [
            'httpCode' => 200,
            'result' => $foundBooks
        ];
    }
    return $result;
};