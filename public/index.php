<?php
use Infrastructure\AppConfig;

require_once __DIR__ . '/../src/Infrastructure/app.function.php';
require_once __DIR__ . '/../src/Infrastructure/AppConfig.php';
require_once __DIR__ . '/../src/Infrastructure/Logger/Factory.php';


$resultApp = \Infrastructure\app
(
    include __DIR__ . '/../config/request.handlers.php',
    $_SERVER['REQUEST_URI'],
    '\Infrastructure\Logger\Factory::create',
    static function() {return AppConfig::createFromArray(include __DIR__ . '/../config/dev/config.php');}

);
\Infrastructure\render($resultApp['result'], $resultApp['httpCode']);
