<?php

namespace EfTech\BookLibrary\Config;

use EfTech\BookLibrary\Infrastructure\HttpApplication\AppConfig as BaseConfig;

/**
 *  Конфиг приложения
 */
class AppConfig extends BaseConfig
{


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
    protected function setLoginUri(string $loginUri): AppConfig
    {
        $this->loginUri = $loginUri;
        return $this;
    }
}