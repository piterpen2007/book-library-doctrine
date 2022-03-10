<?php
$dsn = "pgsql:host=localhost;port=5432;dbname=book_library_db";
$dbConn = new PDO($dsn,'postgres','');

$title = 'title_1646910144889947_0';

$baseSql = <<<EOF
 SELECT title, date, number, type
 FROM text_document_test
WHERE  title =:title
EOF;


$totalDbTime = 0;
$stmt = $dbConn->prepare($baseSql);

$startTime = hrtime(true);
$stmt->execute(
    [
        'title' => $title
    ]
);
$row = $stmt->fetchAll();
$totalDbTime += hrtime(true) - $startTime;

echo "rows: " . count($row) . ". Total time " . $totalDbTime/1000000000.0 . "сек\n";