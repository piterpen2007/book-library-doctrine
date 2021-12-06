<?php

require_once __DIR__ . '/app.function.php';

$resultApp = app
(
    include  __DIR__ . '/request.handlers.php',
    $_SERVER['REQUEST_URI'],
    $_GET,
    'loggerInFile'
);
render($resultApp['result'], $resultApp['httpCode']);