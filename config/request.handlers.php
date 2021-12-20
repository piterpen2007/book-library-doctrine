<?php

use EfTech\BookLibrary\Controller\FindAuthors;
use EfTech\BookLibrary\Controller\FindBooks;
use EfTech\BookLibrary\Infrastructure\AppConfig;
use EfTech\BookLibrary\Infrastructure\http\ServerRequest;
use EfTech\BookLibrary\Infrastructure\Logger\LoggerInterface;

return [
    '/books' => static function(ServerRequest $serverRequest,LoggerInterface $logger, AppConfig $appConfig) {
        return (new FindBooks($logger,$appConfig))($serverRequest);
    },
    '/authors' => static function(ServerRequest $serverRequest,LoggerInterface $logger, AppConfig $appConfig) {
        return (new FindAuthors($logger,$appConfig))($serverRequest);
    },
];
