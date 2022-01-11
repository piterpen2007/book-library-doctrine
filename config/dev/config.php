<?php
return [
    /**
     *  Путь до файла с данными о авторе
     */
    '$pathToAuthor' => __DIR__ . '/../../data/authors.json',
    /**
     *  Путь до файла с данными о книге
     */
    'pathToBooks' => __DIR__ . '/../../data/books.json',
    /**
     *  Путь до файла с данными о журнале
     */
    'pathToMagazines' => __DIR__ . '/../../data/magazines.json',
    /**
     * Путь до файла куда пищем логи
     */
    'pathToLogFile' => __DIR__ . '/../../var/log/app.log',
    /**
     * Тип используемого логера
     */
    'loggerType' => 'fileLogger',
    'hideErrorMsg' => false
];