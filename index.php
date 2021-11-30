<?php

/**
 * Функция валидации
 *
 * @param string $paramName - имя параметра
 * @param array $param - имя запроса
 * @param string $errorMessage - сообщение об ошибке
 */
function paramTypeValidation(string $paramName, array $param, string $errorMessage)
{
    if (array_key_exists($paramName, $param) && false === is_string($param[$paramName])) {
        errorHandling($errorMessage, 500, 'fail');
    }
}

/**
 * Логирует текстовое сообщение
 * @param string $errMsg - сообщение о ошибке
 */
function logger ( string $errMsg):void
{
    file_put_contents(__DIR__ . '/app.log',"{$errMsg}\n", FILE_APPEND);
}
/**
 * @param array $data - данные, которые хотим отобразить
 * @param int $httpCode - http code
 */
function render(array $data, int $httpCode)
{
    header('Content-Type: application/json');
    http_response_code($httpCode);
    echo json_encode($data);
    exit();
}

/**
 * Обработка ошибок
 * @param string $message - сообщение о причине ошибке
 * @param int $httpCode - http code
 * @param string $status - статус ошибки
 */
function errorHandling(string $message, int $httpCode, string $status): void
{
    $result = [
        'status' => $status,
        'message' => $message
    ];
    logger($message);
    render($result, $httpCode);
}

/**
 * @param string $sourceName - путь до файла
 * @return array - вывод содержимого файла в виде массива
 */
function loadData (string $sourceName):array
{
    $pathToFile = __DIR__ . "/{$sourceName}.json";
    $content = file_get_contents($pathToFile);
    return json_decode($content, true);
}


logger('Url request received' . $_SERVER['REQUEST_URI']);

$pathInfo = array_key_exists('PATH_INFO', $_SERVER) && $_SERVER['PATH_INFO'] ? $_SERVER['PATH_INFO'] : '';

if ('/books' === $pathInfo) {
    $authorsJson = loadData('authors');
    $booksJson = loadData('books');

    $httpCode = 200;
    $result = [];
    logger('dispatch "books" url');
    paramTypeValidation('author_surname',$_GET,'inccorrect author surname');
    paramTypeValidation('title', $_GET,'inccorrect title book');
    $authorIdToInfo = [];
    foreach ($authorsJson as $info) {
        $authorIdToInfo[$info['id']] = $info;
    }

    foreach ($booksJson as $book) {
        if (array_key_exists('author_surname', $_GET)) {
            $bookMeetSearchCriteria = $_GET['author_surname'] === $authorIdToInfo[$book['author_id']]['surname'];
        } else {
            $bookMeetSearchCriteria = true;
        }

        if ($bookMeetSearchCriteria && array_key_exists('title', $_GET)) {
            $bookMeetSearchCriteria = $_GET['title'] === $book['title'];
        }

        if ($bookMeetSearchCriteria) {
            $book['author'] = $authorIdToInfo[$book['author_id']];
            unset($book['author_id']);
            $result[] = $book;
        }
    }
    logger('found books ' . count($result));
} elseif ('/authors' === $pathInfo) {
    $authorsJson = loadData('authors');

    $httpCode = 200;
    $result = [];
    $searchParamCorrect = true;

    logger('dispatch "authors" url');
    paramTypeValidation('surname', $_GET,'inccorrect surname author');
    foreach ($authorsJson as $currentAuthor) {
        if (array_key_exists('surname', $_GET) && $currentAuthor['surname'] === $_GET['surname']) {
            $result[] = $currentAuthor;
        }
    }
    logger('found authors: ' . count($result));
} else {
    errorHandling('unsupported request', 404, 'fail');
}
render($result, $httpCode);