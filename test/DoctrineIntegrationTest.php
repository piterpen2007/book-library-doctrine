<?php

namespace EfTech\BookLibraryTest;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use EfTech\BookLibrary\Config\ContainerExtensions;
use EfTech\BookLibrary\Entity\User;
use EfTech\BookLibrary\Infrastructure\DI\SymfonyDiContainerInit;
use PHPUnit\Framework\TestCase;

class DoctrineIntegrationTest extends TestCase
{
    /**\
     * Тестирование подключения доктрины
     * @return void
     * @throws \Exception
     */
    public function testCreateEntityManager(): void
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

    /**
     * Проверяю что доктрина успешна загружает пользователя
     *
     * @throws \Exception
     */
    public function testLoadUsers(): void
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
        //act
        $user = $em->getRepository(User::class);
        $user->findOneBy(['login' => 'admin']);

        //Assert
        $this->assertInstanceOf(User::class, $user);
    }

    public function testDoctrineEventSubscriber(): void
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

        $eventSubscriber = new class implements EventSubscriber
        {
            /** @var LifecycleEventArgs */
            public $args;

            public function getSubscribedEvents(): array
            {
                return [Events::postLoad];
            }
            public function postLoad($args): void
            {
                $this->args = $args;
            }
        };

        $em->getEventManager()->addEventSubscriber($eventSubscriber);

        //act
        $user = $em->getRepository(User::class)->findOneBy(['login' => 'admin']);

        //Assert
        $this->assertInstanceOf(LifecycleEventArgs::class, $eventSubscriber->args);
        $this->assertEquals($user, $eventSubscriber->args->getEntity());
    }
}
