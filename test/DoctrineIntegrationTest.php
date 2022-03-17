<?php

namespace EfTech\BookLibraryTest;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use EfTech\BookLibrary\Config\ContainerExtensions;
use EfTech\BookLibrary\Infrastructure\DI\SymfonyDiContainerInit;
use PHPUnit\Framework\TestCase;

class DoctrineIntegrationTest extends TestCase
{

    /**\
     * Тестирование подключения доктрины
     * @return void
     * @throws \Exception
     */
    public function testCreateEntityManager() :void
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

        //Act
        $em = $diContainer->get(EntityManagerInterface::class);


        //Assert
        $this->assertInstanceOf(EntityManagerInterface::class, $em);


    }
}
