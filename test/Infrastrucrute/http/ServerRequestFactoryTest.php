<?php

namespace EfTech\BookLibrary\Infrastructure\http;

use EfTech\BookLibrary\Infrastructure\Autoloader;

require_once __DIR__ . '/../../../src/Infrastructure/Autoloader.php';

spl_autoload_register(
    new Autoloader([
        'EfTech\\BookLibrary\\' => __DIR__ . '/../../../src/',
        'EfTech\\BookLibraryTest\\' => __DIR__ . '/../../../test/'
    ])
);
/**
 *  Тестирует логику работу фабрики, создающий серверный http запрос
 */
final class ServerRequestFactoryTest
{
    public static function testCreateFromGlobals():void
    {
        echo "-----------Тестирует логику работу фабрики, создающий серверный http запрос----------\n";

        //Arrange
        $servers = [
            'SERVER_PROTOCOL' => 'HTTP/1.1',
            'SERVER_PORT' => '80',
            'REQUEST_URI' => '/samhtml/ssylki/absolyutnye-i-otnositelnye-ssylki?query=value1#fragment-example',
            'REQUEST_METHOD' => 'GET',
            'SERVER_NAME' => 'localhost',

            'HTTP_HOST'       =>  'localhost:80',
            'HTTP_CONNECTION' =>  'Keep-Alive',
            'HTTP_USER_AGENT' =>  'Apache-HttpClient\/4.5.13 (Java\/11.0.11)',
            'HTTP_COOKIE'     =>  'XDEBUG_SESSION=16151',
        ];
        //Act
        $httpServerRequest = ServerRequestFactory::createFromGlobals($servers);
        //Assert
        $expected = 'http://localhost:80/samhtml/ssylki/absolyutnye-i-otnositelnye-ssylki?query=value1#fragment-example';
        $actual = (string)$httpServerRequest->getUri();

        //Assert
        if($expected === $actual) {
            echo "      ОК - объект ServerRequestFactory корректно создан\n";
        } else {
            echo  "      FAIL - объект ServerRequestFactory не корректно создан, ожидалось $expected.\n Актуальное значение $actual\n";

        }
    }
}

ServerRequestFactoryTest::testCreateFromGlobals();