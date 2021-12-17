<?php

namespace EfTech\BookLibraryTest\Infrastructure\Uri;

use EfTech\BookLibrary\Infrastructure\Autoloader;
use EfTech\BookLibrary\Infrastructure\Uri\Uri;

require_once __DIR__ . '/../../../src/Infrastructure/Autoloader.php';

spl_autoload_register(
    new Autoloader([
        'EfTech\\BookLibrary\\' => __DIR__ . '/../../../src/',
        'EfTech\\BookLibraryTest\\' => __DIR__ . '/../../../test/'
    ])
);

/** Тестирование Uri
 *
 */
final class UriTest
{
    /** Тестирование преобразования объекта URI в строку
     *
     */
    public static function testUriToString():void
    {
        echo "-------- Тестирование преобразования объекта Uri в строку --------\n";
        // Arrange
        $expected = 'http://and:mypassword@htmlbook.ru:80/samhtml/ssylki/absolyutnye-i-otnositelnye-ssylki?query=value1#fragment-example';
        $uri = new Uri(
            'http',
            'htmlbook.ru',
            '80',
            '/samhtml/ssylki/absolyutnye-i-otnositelnye-ssylki',
            'query=value1',
            'and:mypassword',
            'fragment-example'
        );
        //Act
        $actual = (string)$uri;

        //Assert
        if($expected === $actual) {
            echo "      ОК - объект uri корректно преобразован в строку\n";
        } else {
            echo  "      FAIL - объект uri не корректно преобразован в строку, ожидалось $expected.\n Актуальное значение $actual\n";

        }
    }

    /** Тестирование создание объекта URI из строки
     *
     */
    public static function testCreateFromString():void
    {
        echo "-------- Тестирование создание объекта URI из строки --------\n";
        // Arrange
        $expected = 'http://and:mypassword@htmlbook.ru:80/samhtml/ssylki/absolyutnye-i-otnositelnye-ssylki?query=value1#fragment-example';

        //Act
        $uri = Uri::createFromString($expected);
        $actual = (string)$uri;


        //Assert
        if($expected === $actual) {
            echo "      ОК - объект uri корректно создан из строки\n";
        } else {
            echo  "      FAIL - объект uri не корректно создан из строки, ожидалось $expected.\n Актуальное значение $actual\n";

        }
    }
}

UriTest::testUriToString();
UriTest::testCreateFromString();