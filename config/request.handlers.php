<?php

use EfTech\BookLibrary\Controller\FindAuthors;
use EfTech\BookLibrary\Controller\FindBooks;


return [
    '/books' => FindBooks::class,
    '/authors' => FindAuthors::class
];
