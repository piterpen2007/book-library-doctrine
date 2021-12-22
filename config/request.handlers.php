<?php

use EfTech\BookLibrary\Controller\FindAuthors;
use EfTech\BookLibrary\Controller\FindBooks;
use EfTech\BookLibrary\Infrastructure\AppConfig;
use EfTech\BookLibrary\Infrastructure\http\ServerRequest;
use EfTech\BookLibrary\Infrastructure\Logger\LoggerInterface;

return [
    '/books' => FindBooks::class,
    '/authors' => FindAuthors::class
];
