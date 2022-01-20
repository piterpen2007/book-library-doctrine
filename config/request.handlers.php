<?php

use EfTech\BookLibrary\Controller\GetAuthorsCollectionController;
use EfTech\BookLibrary\Controller\GetBooksCollectionController;
use EfTech\BookLibrary\Controller\TextDocumentAdministrationController;


return [
    '/books' => GetBooksCollectionController::class,
    '/authors' => GetAuthorsCollectionController::class,
    '/text-document/administration' => TextDocumentAdministrationController::class,
    '/login' => \EfTech\BookLibrary\Controller\LoginController::class
];
