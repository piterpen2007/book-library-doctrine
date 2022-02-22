#!/usr/bin/env_php
<?php
$dsn = "pgsql:host=localhost;port=5432;dbname=book_library_db";
$dbConn = new PDO($dsn,'postgres','');

//$dbConnect = pg_connect('host=localhost dbname=book_library_db user=postgres password=Qwerty12');



/**
 * Импорт данных пользователя
 */
$dbConn->query('DELETE FROM users');

$userData = json_decode(file_get_contents(__DIR__ . '/../data/users.json'), true, 512, JSON_THROW_ON_ERROR);

foreach ($userData as $authorItem) {
    $sql = "INSERT INTO users(id, login, password) values ({$authorItem['id']}, '{$authorItem['login']}', '{$authorItem['password']}')";
    echo $sql;
    $dbConn->query($sql);
}

$userFromDb = $dbConn->query('SELECT * FROM users')->fetchAll(PDO::FETCH_ASSOC);

/**
 * Импорт текстовых документов
 */

$dbConn->query('DELETE FROM text_documents');

$textDocumentJsonData = [
        'book' => json_decode(file_get_contents(__DIR__ . '/../data/books.json'), true, 512, JSON_THROW_ON_ERROR),
        'magazine' => json_decode(file_get_contents(__DIR__ . '/../data/magazines.json'), true, 512, JSON_THROW_ON_ERROR)
];

foreach ($textDocumentJsonData as $type => $textDocumentCollection) {
    foreach ($textDocumentCollection as $textDocItem) {
        $number = $textDocItem['number'] ?? 'null';
        $authorId = $textDocItem['author_id'] ?? 'null';

        $sql = <<<EOF
INSERT INTO text_documents (id, title, year, status, number, author_id, type)
VALUES 
(
    {$textDocItem['id']},
    '{$textDocItem['title']}',
    '{$textDocItem['year']}/01/01',
    '{$textDocItem['status']}',
    $number,
    $authorId,
    '$type'
)
EOF;
        echo $sql . "\n";
        $dbConn->query($sql);
    }
}

/**
 * Импорт авторов
 */
$dbConn->query('DELETE FROM authors');

$authorJsonData = json_decode(file_get_contents(__DIR__ . '/../data/authors.json'), true, 512, JSON_THROW_ON_ERROR);
foreach ($authorJsonData as $authorItem) {
    $sql = <<<EOF
INSERT INTO authors (id, name, surname, birthday, country)
VALUES 
(
    {$authorItem['id']},
    '{$authorItem['name']}',
    '{$authorItem['surname']}',
    '{$authorItem['birthday']}',
    '{$authorItem['country']}'
)
EOF;

    echo $sql;
    $dbConn->query($sql);
}

$authorFromDb = $dbConn->query('SELECT * FROM authors')->fetchAll(PDO::FETCH_ASSOC);


/**
 * Импорт текстовых документов
 */

$dbConn->query('DELETE FROM purchase_price');

$textDocumentJsonData = [
    'book' => json_decode(file_get_contents(__DIR__ . '/../data/books.json'), true, 512, JSON_THROW_ON_ERROR),
    'magazine' => json_decode(file_get_contents(__DIR__ . '/../data/magazines.json'), true, 512, JSON_THROW_ON_ERROR)
];

foreach ($textDocumentJsonData as $type => $textDocumentCollection) {
    foreach ($textDocumentCollection as $textDocItem) {
        foreach ($textDocItem['purchase_price'] as $purchaseItem) {
            $sql = <<<EOF
INSERT INTO purchase_price (text_document_id,price, currency, date)
VALUES 
(
    {$textDocItem['id']},
    {$purchaseItem['price']},
    '{$purchaseItem['currency']}',
    '{$purchaseItem['date']}'
)
EOF;
            echo $sql . "\n";
            $dbConn->query($sql);
        }
    }
}


