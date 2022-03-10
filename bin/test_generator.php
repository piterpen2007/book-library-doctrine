<?php
$dsn = "pgsql:host=localhost;port=5432;dbname=book_library_db";
$dbConn = new PDO($dsn,'postgres','');

$maxRow = 1000000;
$baseSql = <<<EOF
INSERT 
INTO text_document_test (title, date, number, type) values 
EOF;

$totalDbTime = 0;

$insertRows = [];
$numberOfInsertRows = 1000;
$lastTitle = '';

for ($i = 0; $i <= $maxRow; $i++) {
    $title = sprintf('title_%d_%d', microtime(true)*1000000, $i);
    $date = (new DateTimeImmutable())->format('Y-m-d');
    $type = $i % 2 === 0 ? 'book' : 'magazine';
    $insertRows[] = "( '$title', '$date', $i , '$type')";
    $lastTitle = $title;
    if (count($insertRows) === $numberOfInsertRows || $i === $maxRow) {
        $sql = $baseSql . implode(',', $insertRows);
        $startTime = hrtime(true);
        $dbConn->query($sql);
        $totalDbTime += hrtime(true) - $startTime;
        $insertRows = [];
    }

}

echo "rows: $maxRow. Total time " . $totalDbTime/1000000000.0 . "сек\n";
echo "Last title: $lastTitle";