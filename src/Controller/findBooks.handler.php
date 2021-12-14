<?php
namespace EfTech\BookLibrary\Controller;
use EfTech\BookLibrary\Entity\Author;
use EfTech\BookLibrary\Entity\Book;
use EfTech\BookLibrary\Entity\Magazine;
use EfTech\BookLibrary\Infrastructure\AppConfig;
use EfTech\BookLibrary\Infrastructure\Logger\LoggerInterface;
use function EfTech\BookLibrary\Infrastructure\loadData;
use function EfTech\BookLibrary\Infrastructure\paramTypeValidation;

require_once __DIR__ . '/../Infrastructure/app.function.php';
/** Функция поиска книги
* @param $request array - параметры которые передаёт пользователь
* @logger callable - параметр инкапсулирующий логгирование
* @return array - возвращает результат поиска по книгам
*/
return static function (array $request, LoggerInterface $logger, AppConfig $appConfig):array
{
    $authorsJson = loadData($appConfig->getPathToAuthor());
    $booksJson   = loadData($appConfig->getPathToBooks());
    $magazinesJson = loadData($appConfig->getPathToMagazines());

    $logger->log('dispatch "books" url');

    $paramValidations = [
        'author_surname' => 'inccorrect author surname',
        'title' =>'inccorrect title book'
    ];

    $booksJson = array_merge($booksJson,$magazinesJson);

    if(null === ($result = paramTypeValidation($paramValidations, $request))) {
        $foundBooks = [];
        $authorIdToInfo = [];
        foreach ($authorsJson as $info) {
            $authorObj = Author::createFromArray($info);

            $authorIdToInfo[$authorObj->getId()] = $authorObj;
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
                $book['author'] = null === $book['author_id'] ? null : $authorIdToInfo[$book['author_id']];
                if (array_key_exists('number',$book)) {
                    $bookObj = Magazine::createFromArray($book);
                } else {
                    $bookObj = Book::createFromArray($book);
                }
                $foundBooks[] = $bookObj;
            }
        }
        $logger->log('found books ' . count($foundBooks));
        return [
            'httpCode' => 200,
            'result' => $foundBooks
        ];
    }
    return $result;
};