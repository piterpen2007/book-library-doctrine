<?php

/**
 *  Конфиг приложения
 */
class AppConfig
{
    /** Путь до файла с данными о авторе
     * @var string
     */
    private string $pathToAuthor = __DIR__ . '/../../data/authors.json';
    /** Путь до файла с данными о книгах
     * @var string
     */
    private string $pathToBooks = __DIR__ . '/../../data/books.json';
    /** Путь до файла с данными о журналах
     * @var string
     */
    private string $pathToMagazines = __DIR__ . '/../../data/magazines.json';

    /**
     * @return string
     */
    public function getPathToAuthor(): string
    {
        return $this->pathToAuthor;
    }

    /**
     * @param string $pathToAuthor
     * @return AppConfig
     *
     * @throws Exception
     */
    private function setPathToAuthor(string $pathToAuthor): AppConfig
    {
        $this->validateFilePath($pathToAuthor);
        $this->pathToAuthor = $pathToAuthor;
        return $this;
    }

    /**
     * @return string
     */
    public function getPathToBooks(): string
    {
        return $this->pathToBooks;
    }

    /**
     * @param string $pathToBooks
     * @return AppConfig
     * @throws Exception
     */
    private function setPathToBooks(string $pathToBooks): AppConfig
    {
        $this->validateFilePath($pathToBooks);
        $this->pathToBooks = $pathToBooks;
        return $this;
    }

    /**
     * @return string
     */
    public function getPathToMagazines(): string
    {
        return $this->pathToMagazines;
    }

    /**
     *
     * @param string $pathToMagazines
     * @return AppConfig
     * @throws Exception
     */
    private function setPathToMagazines(string $pathToMagazines): AppConfig
    {
        $this->validateFilePath($pathToMagazines);
        $this->pathToMagazines = $pathToMagazines;
        return $this;
    }

    /**
     * @param string $path
     * @return void
     * @throws Exception
     */
    private function validateFilePath(string $path):void
    {
        if(false === file_exists($path)) {
            throw new Exception('Некорректный путь до файла с данными');
        }
    }

    /**Создает конфиг приложения из массива
     * @param array $config
     * @return static
     * @uses AppConfig::setPathToBooks()
     * @uses AppConfig::setPathToAuthor()
     * @uses AppConfig::setPathToMagazines()
     */
    public static function createFromArray(array $config):self
    {
        $appConfig = new self();

        foreach ($config as $key => $value) {
            if (property_exists($appConfig, $key)) {
                $setter = 'set' . ucfirst($key);
                $appConfig->{$setter}($value);
            }
        }

        return $appConfig;
    }

}