<?php

require_once __DIR__ . '/app.function.php';
require_once __DIR__ . '/AppConfig.php';



$resultApp = app
(
    include  __DIR__ . '/request.handlers.php',
    $_SERVER['REQUEST_URI'],
    'loggerInFile',
    static function() {return AppConfig::createFromArray(include __DIR__ . '/dev.env.config.php');}

);
render($resultApp['result'], $resultApp['httpCode']);
