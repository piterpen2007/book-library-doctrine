<?php
$b = 3;
$f = function (string &$arg):void {
    $arg = 5;
};
$f($b);
/**
 * Функция валидации
 *
 * @param array $validateParameters - валидируемые параметры, ключ имя параметра, а значение это текст сообщения о ошибке
 * @param array $params - все множество параметров
 * @return array - сообщение о ошибках
 */
function paramTypeValidation(array $validateParameters, array $params):?array
{
  $result = null;
  foreach ($validateParameters as $paramName => $errMsg) {
      if (array_key_exists($paramName, $params) && false === is_string($params[$paramName])) {
          $result = [
                'httpCode' => '500',
                'result' => [
                    'status' => 'fail',
                    'message' => $errMsg
                ]
          ];
          break;
      }
  }
  return $result;
}

/**
 * Логирует текстовое сообщение
 * @param string $errMsg - сообщение о ошибке
 */
function loggerInFile ( string $errMsg):void
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
 * @param string $sourceName - путь до файла
 * @return array - вывод содержимого файла в виде массива
 */
function loadData (string $sourceName):array
{
    $pathToFile = __DIR__ . "/{$sourceName}.json";
    $content = file_get_contents($pathToFile);
    return json_decode($content, true);
}

/** Функция поиска книги
 * @param $request array - параметры которые передаёт пользователь
 * @logger callable - параметр инкапсулирующий логгирование
 * @return array - возвращает результат поиска по книгам
 */
function findBooks (array $request, callable $logger):array
{
    $authorsJson = loadData('authors');
    $booksJson   = loadData('books');

    $logger('dispatch "books" url');

    $paramValidations = [
        'author_surname' => 'inccorrect author surname',
        'title' =>'inccorrect title book'
    ];

    if(null === ($result = paramTypeValidation($paramValidations, $request))) {
        $foundBooks = [];
        $authorIdToInfo = [];
        foreach ($authorsJson as $info) {
            $authorIdToInfo[$info['id']] = $info;
        }

        foreach ($booksJson as $book) {
            if (array_key_exists('author_surname', $request)) {
                $bookMeetSearchCriteria = $request['author_surname'] === $authorIdToInfo[$book['author_id']]['surname'];
            } else {
                $bookMeetSearchCriteria = true;
            }

            if ($bookMeetSearchCriteria && array_key_exists('title', $request)) {
                $bookMeetSearchCriteria = $request['title'] === $book['title'];
            }

            if ($bookMeetSearchCriteria) {
                $book['author'] = $authorIdToInfo[$book['author_id']];
                unset($book['author_id']);
                $foundBooks[] = $book;
            }
        }
        $logger('found books ' . count($foundBooks));
        return [
            'httpCode' => 200,
            'result' => $foundBooks
        ];
    }
    return $result;
}

/** Функция поиска авторов
 * @param $request array - параметры которые передаёт пользователь
 * @logger callable - параметр инкапсулирующий логгирование
 * @return array - возвращает результат поиска по авторам
 */
function findAuthors (array $request, callable $logger):array
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
                $foundAuthor[] = $currentAuthor;
            }
        }
        $logger('found authors: ' . count($foundAuthor));
        return [
            'httpCode' => 200,
            'result' => $foundAuthor
        ];
    }
    return $result;

}

/** Функция реализации веб приложения
 * @param $requestUri string - URI запроса
 * @param $request array - параметры которые передаёт пользователь
 * @logger callable - параметр инкапсулирующий логгирование
 * @return array
 */
function app (string $requestUri ,array $request,callable $logger):array
{
    $logger('Url request received' . $requestUri);
    $urlPath = parse_url($requestUri, PHP_URL_PATH);

    if ('/books' === $urlPath) {
        $result = findBooks($request, $logger);
    } elseif ('/authors' === $urlPath) {
        $result = findAuthors($request, $logger);
    } else {
        $result = [
            'httpCode' => 404,
            'result' => [
                'status' => 'fail',
                'message' => 'unsupported request'
            ]
        ];
        $logger($result['result']['message']);
    }
    return $result;
}


$resultApp = app
(
    $_SERVER['REQUEST_URI'],
    $_GET,
    'loggerInFile'
);
render($resultApp['result'], $resultApp['httpCode']);