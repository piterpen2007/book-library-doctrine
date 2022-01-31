<?php

namespace EfTech\BookLibraryTest;

use EfTech\BookLibrary\Config\AppConfig;
use EfTech\BookLibrary\ConsoleCommand\HashStr;
use EfTech\BookLibrary\Infrastructure\DI\SymfonyDiContainerInit;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 *  Тестирование создания сервисов приложения
 */
class DiAppServiceTest extends TestCase
{
    /**
     *
     *
     * @return array
     */
    public static function serviceDataProvider(): array
    {
        return [
            HashStr::class => [
                '$serviceId' => HashStr::class,
                '$expectedServiceClass' => HashStr::class
            ],
            AppConfig::class => [
                'serviceId' => AppConfig::class,
                'expectedServiceClass' => AppConfig::class
            ]
        ];
    }
    /** Проверяет корректность создания сервиса через di контейнер
     *
     * @dataProvider serviceDataProvider
     * @param string $serviceId
     * @param string $expectedServiceClass
     * @throws Exception
     */
    public function testCreateService(string $serviceId, string $expectedServiceClass): void
    {
        //Arrange
        $diContainerFactory = new SymfonyDiContainerInit(__DIR__ . '/../config/dev/di.xml');
        $diContainer = $diContainerFactory();

        //Act
        $actualService = $diContainer->get($serviceId);

        //Assert
        $this->assertInstanceOf($expectedServiceClass, $actualService);
    }
}
