<?php
$pathToAuthors = __DIR__ . '/authors.json';
$authorsTxt = file_get_contents($pathToAuthors);
$authorsJson = json_decode($authorsTxt,true);

$pathToBooks = __DIR__ . '/books.json';
$booksTxt = file_get_contents($pathToBooks);
$booksJson = json_decode($booksTxt,true);

if('/books' === $_SERVER['PATH_INFO']) {
    $httpCode = 200;
    $result = [];
    foreach ($booksJson as $book) {
        if (array_key_exists('title', $_GET) && $book['title'] === $_GET['title']) {
            $result[] = $book;
        }
    }
} elseif ('/authors' === $_SERVER['PATH_INFO']) {
    $httpCode = 200;
    $result = [];
    foreach ($authorsJson as $author) {
        if (array_key_exists('surname',$_GET) && $author['surname'] === $_GET['surname']) {
            $result[] = $author;
        }
    }
} else {
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