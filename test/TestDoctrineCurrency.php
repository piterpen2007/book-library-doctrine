<?php

namespace EfTech\BookLibraryTest;

use Doctrine\ORM\EntityManagerInterface;
use EfTech\BookLibrary\Config\ContainerExtensions;
use EfTech\BookLibrary\Infrastructure\DI\SymfonyDiContainerInit;
use EfTech\BookLibrary\ValueObject\Currency;
use PHPUnit\Framework\TestCase;

class TestDoctrineCurrency extends TestCase
{
    public function testGetCurrency(): void
    {
        //Arrange
        $diContainerFactory = new SymfonyDiContainerInit(
            new SymfonyDiContainerInit\ContainerParams(
                __DIR__ . '/../config/dev/di.xml',
                [
                    'kernel.project_dir' => __DIR__ . '/../'
                ],
                ContainerExtensions::httpAppContainerExtension()
            )
        );
        $diContainer = $diContainerFactory();
        /** @var EntityManagerInterface $em */
        $em = $diContainer->get(EntityManagerInterface::class);

        $currency = $em->getRepository(Currency::class)->findOneBy(['name' => 'RUB']);

        $this->assertEquals('Рубль', $currency->getDescription(), 'Некорректная валюта');
    }
}
