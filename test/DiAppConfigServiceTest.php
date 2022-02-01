<?php

namespace EfTech\BookLibraryTest;

use EfTech\BookLibrary\Config\AppConfig;
use EfTech\BookLibrary\Infrastructure\DI\SymfonyDiContainerInit;
use Exception;
use PHPUnit\Framework\TestCase;

class DiAppConfigServiceTest extends TestCase
{
    /** Поставщик данных для теста
     * @return array[]
     */
    public static function appConfigDataProvider(): array
    {
        return [
            'pathToAuthor' => [
                'method' => 'getPathToAuthor',
                'expectedValue' => __DIR__ . '/../data/authors.json',
                'isPath' => true
            ],
            'pathToBooks' => [
                'method' => 'getPathToBooks',
                'expectedValue' => __DIR__ . '/../data/books.json',
                'isPath' => true
            ],
            'pathToMagazines' => [
                'method' => 'getPathToMagazines',
                'expectedValue' => __DIR__ . '/../data/magazines.json',
                'isPath' => true
            ],
            'pathToLogFile' => [
                'method' => 'getPathToLogFile',
                'expectedValue' => __DIR__ . '/../var/log/app.log',
                'isPath' => true
            ],
            'pathToUsers' => [
                'method' => 'getPathToUsers',
                'expectedValue' => __DIR__ . '/../data/users.json',
                'isPath' => true
            ],
            'loginUri' => [
                'method' => 'getLoginUri',
                'expectedValue' => '/login',
                'isPath' => false
            ],
            'hideErrorMsg' => [
                'method' => 'isHideErrorMsg',
                'expectedValue' => false,
                'isPath' => false
            ],
        ];
    }

    /** Тестирование получения значений из конфига приложений
     *
     *
     * @dataProvider appConfigDataProvider
     * @param string $method
     * @param $expectedValue
     * @param bool $isPath
     * @throws Exception
     */
    public function testAppConfigGetter(string $method, $expectedValue, bool $isPath): void
    {
        //Arrange
        $diContainerFactory = new SymfonyDiContainerInit(
            __DIR__ . '/../config/dev/di.xml',
            [
            'kernel.project_dir' => __DIR__ . '/../'
            ]
        );
        $diContainer = $diContainerFactory();
        $appConfig = $diContainer->get(AppConfig::class);

        //Act
        $actualValue = $appConfig->$method();

        //Assert
        if ($isPath) {
            $expectedValue = realpath($expectedValue);
            $actualValue = realpath($actualValue);
        }
        $this->assertSame($actualValue, $expectedValue);
    }
}
