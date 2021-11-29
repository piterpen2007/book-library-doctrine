<?php
$pathToAuthors = __DIR__ . '/authors.json';
$authorsTxt = file_get_contents($pathToAuthors);
$authorsJson = json_decode($authorsTxt,true);

$pathToBooks = __DIR__ . '/books.json';
$booksTxt = file_get_contents($pathToBooks);
$booksJson = json_decode($booksTxt,true);

$pathToLogFile = __DIR__ . '/app.log';

file_put_contents($pathToLogFile,'Url request received' . $_SERVER['REQUEST_URI'] . "\n", FILE_APPEND);

$pathInfo = array_key_exists('PATH_INFO',$_SERVER) && $_SERVER['PATH_INFO'] ? $_SERVER['PATH_INFO'] : '';

if('/books' === $pathInfo) {
    $httpCode = 200;
    $result = [];
    $searchParamCorrect = true;

    file_put_contents($pathToLogFile,'dispatch "books" url' . "\n", FILE_APPEND);

    if (array_key_exists('author_surname',$_GET ) && false === is_string($_GET['author_surname'])) {

        file_put_contents($pathToLogFile,'Incorrect author_surname' . "\n", FILE_APPEND);

        $result = [
            'status' => 'fail',
            'message' => 'inccorrect author surname'
            ];
        $httpCode = 500;
        $searchParamCorrect = false;
    }
    if (array_key_exists('title',$_GET ) && false === is_string($_GET['title'])) {

        file_put_contents($pathToLogFile,'Incorrect title book' . "\n", FILE_APPEND);

        $result = [
            'status' => 'fail',
            'message' => 'inccorrect title book'
        ];
        $httpCode = 500;
        $searchParamCorrect = false;
    }
    if ($searchParamCorrect) {
        $authorIdToInfo = [];
        foreach ($authorsJson as $info) {
            $authorIdToInfo[$info['id']] = $info;
        }

        foreach ($booksJson as $book) {

            if(array_key_exists('author_surname', $_GET)) {
                $bookMeetSearchCriteria = $_GET['author_surname'] === $authorIdToInfo[$book['author_id']]['surname'];
            } else {
                $bookMeetSearchCriteria = true;
            }

            if ($bookMeetSearchCriteria && array_key_exists('title', $_GET)) {
                $bookMeetSearchCriteria = $_GET['title'] === $book['title'];
            }

            if ($bookMeetSearchCriteria ) {
                $book['author'] = $authorIdToInfo[$book['author_id']];
                unset($book['author_id']);
                $result[] = $book;
            }
        }

    }
    file_put_contents($pathToLogFile,'found books ' . count($result) . "\n", FILE_APPEND);

} elseif ('/authors' === $pathInfo) {
    $httpCode = 200;
    $result = [];
    $searchParamCorrect = true;

    file_put_contents($pathToLogFile,'dispatch "authors" url' . "\n", FILE_APPEND);

    if (array_key_exists('surname',$_GET ) && false === is_string($_GET['surname'])) {

        file_put_contents($pathToLogFile,'Incorrect surname author' . "\n", FILE_APPEND);

        $result = [
            'status' => 'fail',
            'message' => 'inccorrect surname author'
        ];
        $httpCode = 500;
        $searchParamCorrect = false;
    }
        if($searchParamCorrect) {
            foreach ($authorsJson as $currentAuthor) {
                if (array_key_exists('surname',$_GET) && $currentAuthor['surname'] === $_GET['surname']) {
                    $result[] = $currentAuthor;
                }
            }
        }

    file_put_contents($pathToLogFile,'found authors: ' . count($result) . "\n", FILE_APPEND);

} else {
    file_put_contents($pathToLogFile,'error url' . "\n", FILE_APPEND);
    $httpCode = 404;
    $result = [
        [
            'status' => 'fail',
            'message' => 'unsupported request'
        ]
    ];
}
header('Content-Type: application/json');
http_response_code($httpCode);
echo json_encode($result);