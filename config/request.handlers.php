<?php

use EfTech\BookLibrary\Controller\GetAuthorsCollectionController;
use EfTech\BookLibrary\Controller\GetBooksCollectionController;


return [
    '/books' => GetBooksCollectionController::class,
    '/authors' => GetAuthorsCollectionController::class
];
