<?php

namespace EfTech\BookLibraryTest;

use Doctrine\ORM\EntityManagerInterface;
use EfTech\BookLibrary\Config\ContainerExtensions;
use EfTech\BookLibrary\Entity\AbstractTextDocument;
use EfTech\BookLibrary\Entity\TextDocument\Status;
use EfTech\BookLibrary\Infrastructure\DI\SymfonyDiContainerInit;
use PHPUnit\Framework\TestCase;

/**
 * Проверка корректности работы статуса текстового документа
 */
class TextDocumentStatusTest extends TestCase
{
    public function testGetStatus(): void
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

        //Action
        $status = $em->getRepository(AbstractTextDocument::class)->findOneBy(['id' => 6])->getStatus();

        //Assert
        $this->assertEquals(Status::STATUS_ARCHIVE, $status->getName(), 'Не корректный статус');
    }
}
