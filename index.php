<?php
if('/books' === $_SERVER['PATH_INFO']) {
    $httpCode = 200;
    $result = [
        [
            'id' => 10,
            'title' => 'Мечтают ли андроиды об электроовцах?',
            'year' => 1966,
            'author_id' => 5
        ]
    ];
} elseif ('/authors' === $_SERVER['PATH_INFO']) {
    $httpCode = 200;
    $result = [
        [
            'id' => 1,
            'name' => 'Чак',
            'surname' => 'Паланик',
            'birthday' => '21.02.1962',
        ]
    ];
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