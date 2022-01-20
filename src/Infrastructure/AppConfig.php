<?php

namespace EfTech\BookLibrary\Infrastructure;
use EfTech\BookLibrary\Exception;

/**
 *  Конфиг приложения
 */
class AppConfig
{
    /** Скрывает сообщения о ошибках
     * @var bool
     */
    private bool $hideErrorMsg;

    /** Возвращает флаг, который указывает что нужно скрывать сообщения о ощибках
     * @return bool
     */
    public function isHideErrorMsg(): bool
    {
        return $this->hideErrorMsg;
    }

    /** Устанавливает флаг указывающий что нужно скрывать сообщение о ошибках
     * @param bool $hideErrorMsg
     */
    private function setHideErrorMsg(bool $hideErrorMsg): void
    {
        $this->hideErrorMsg = $hideErrorMsg;
    }
    /**
     * @var string путь до файла логирования
     */
    private string $pathToLogFile = __DIR__ . '/../../var/log/app.log';

    /** Возвращает путь до файла с логами
     * @return string
     */
    public function getPathToLogFile(): string
    {
        return $this->pathToLogFile;
    }


    /** Устанавливает путь до файла логов
     *
     * @param string $pathToLogFile путь до файла с логами
     * @return AppConfig
     */
    private function setPathToLogFile(string $pathToLogFile): AppConfig
    {
        $this->validateFilePath($pathToLogFile);
        $this->pathToLogFile = $pathToLogFile;
        return $this;
    }

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
    private string $pathToUsers = __DIR__ . '/../../data/users.json';
    /** Возвращает ури логина
     * @var string
     */
    private string $loginUri;

    /**
     * @return string
     */
    public function getLoginUri(): string
    {
        return $this->loginUri;
    }

    /**
     * @param string $loginUri
     * @return AppConfig
     */
    private function setLoginUri(string $loginUri): AppConfig
    {
        $this->loginUri = $loginUri;
        return $this;
    }


    /**
     * @param string $pathToUsers
     */
    private function setPathToUsers(string $pathToUsers): void
    {
        $this->validateFilePath($pathToUsers);
        $this->pathToUsers = $pathToUsers;
    }

    /**
     * @return string
     */
    public function getPathToUsers(): string
    {
        return $this->pathToUsers;
    }


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
     */
    private function validateFilePath(string $path): void
    {
        if (false === file_exists($path)) {
            throw new Exception\ErrorCreateAppConfigException('Некорректный путь до файла с данными');
        }
    }

    /**Создает конфиг приложения из массива
     * @param array $config
     * @return static
     * @uses AppConfig::setPathToBooks()
     * @uses AppConfig::setPathToAuthor()
     * @uses AppConfig::setPathToMagazines()
     * @uses AppConfig::setPathToLogFile()
     * @uses AppConfig::setHideErrorMsg()
     * @uses AppConfig::setPathToUsers()
     * @uses AppConfig::setLoginUri()
     */
    public static function createFromArray(array $config): self
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