<?php
use EfTech\BookLibrary\ConsoleCommand;


return [
    'find-author' => ConsoleCommand\FindAuthors::class,
    'find-books'=> ConsoleCommand\FindBooks::class,
    'hash' => ConsoleCommand\HashStr::class
];
