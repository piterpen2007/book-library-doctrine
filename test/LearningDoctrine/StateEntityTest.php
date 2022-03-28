<?php

namespace EfTech\BookLibraryTest\LearningDoctrine;

use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\UnitOfWork;
use EfTech\BookLibrary\Config\ContainerExtensions;
use EfTech\BookLibrary\Entity\Author;
use EfTech\BookLibrary\Infrastructure\DI\SymfonyDiContainerInit;
use EfTech\BookLibrary\ValueObject\Country;
use EfTech\BookLibrary\ValueObject\FullName;
use PHPUnit\Framework\TestCase;

class StateEntityTest extends TestCase
{
    /**
     * Менеджер сущностей
     *
     * @var EntityManagerInterface|mixed
     */
    private EntityManagerInterface $em;

    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        //Arrange
        $diContainerFactory = new SymfonyDiContainerInit(
            new SymfonyDiContainerInit\ContainerParams(
                __DIR__ . '/../../config/dev/di.xml',
                [
                    'kernel.project_dir' => __DIR__ . '/../../'
                ],
                ContainerExtensions::httpAppContainerExtension()
            )
        );
        $diContainer = $diContainerFactory();
        $this-> em = $diContainer->get(EntityManagerInterface::class);

        parent::setUp();
    }

    /**
     * Тестирование создания сущности автора
     */
    public function testNewAuthor(): void
    {
        //Arrange
        //@see \EfTech\BookLibraryTest\LearningDoctrine\StateEntityTest::setUp

        $author = new Author(
            $this->em->getClassMetadata(Author::class)->idGenerator->generateId($this->em, null),
            new FullName('Толстой', 'Лев'),
            DateTimeImmutable::createFromFormat('Y-m-d', '1959-10-02'),
            $this->em->getRepository(Country::class)->findOneBy(['name' => 'Россия']),
            []
        );

        //Assert
        $this->assertEquals(
            UnitOfWork::STATE_NEW,
            $this->em->getUnitOfWork()->getEntityState($author)
        );
    }

    /**
     * Проверяю что попытка узнать состояние объекта, который не является сущностью, приводит к исключению
     *
     *
     */
    public function testExceptionForNotEntityObject(): void
    {
        $this->expectExceptionMessage('Class "stdClass" is not a valid entity or mapped super class.');
        $this->em->getUnitOfWork()->getEntityState(new \stdClass());
    }

    public function testStateLoadedEntity(): void
    {
        //Assert
        $this->assertEquals(
            UnitOfWork::STATE_MANAGED,
            $this->em->getUnitOfWork()
                ->getEntityState($this->em->getRepository(Author::class)
                    ->findOneBy(['fullName.surname' => 'Паланик']))
        );
    }

    /**
     *  Перевод сущности из состояния нью в состояние менеджмент
     */
    public function testPersist(): void
    {
        //Arrange
        //@see \EfTech\BookLibraryTest\LearningDoctrine\StateEntityTest::setUp

        $author = new Author(
            $this->em->getClassMetadata(Author::class)->idGenerator->generateId($this->em, null),
            new FullName('Толстой', 'Лев'),
            DateTimeImmutable::createFromFormat('Y-m-d', '1959-10-02'),
            $this->em->getRepository(Country::class)->findOneBy(['name' => 'Россия']),
            []
        );
        $this->em->persist($author);
        $this->em->flush($author);

        //Assert
        $this->assertEquals(
            UnitOfWork::STATE_MANAGED,
            $this->em->getUnitOfWork()->getEntityState($author)
        );
    }

    /**
     *  Проверка удаления сущности
     *
     */
    public function testRemove(): void
    {
        //Arrange
        //@see \EfTech\BookLibraryTest\LearningDoctrine\StateEntityTest::setUp

        $author = new Author(
            $this->em->getClassMetadata(Author::class)->idGenerator->generateId($this->em, null),
            new FullName('Толстой', 'Лев'),
            DateTimeImmutable::createFromFormat('Y-m-d', '1959-10-02'),
            $this->em->getRepository(Country::class)->findOneBy(['name' => 'Россия']),
            []
        );
        //Вариант 1
        $this->assertEquals(
            UnitOfWork::STATE_NEW,
            $this->em->getUnitOfWork()->getEntityState($author)
        );
        $this->em->remove($author);
        $this->assertEquals(
            UnitOfWork::STATE_NEW,
            $this->em->getUnitOfWork()->getEntityState($author)
        );

        //Вариант 2
        $this->assertEquals(
            UnitOfWork::STATE_NEW,
            $this->em->getUnitOfWork()->getEntityState($author)
        );
        $this->em->persist($author);
        $this->assertEquals(
            UnitOfWork::STATE_MANAGED,
            $this->em->getUnitOfWork()->getEntityState($author)
        );
        $this->em->remove($author);
        $this->assertEquals(
            UnitOfWork::STATE_NEW,
            $this->em->getUnitOfWork()->getEntityState($author)
        );

        //Вариант 3
        $this->assertEquals(
            UnitOfWork::STATE_NEW,
            $this->em->getUnitOfWork()->getEntityState($author)
        );
        $this->em->persist($author);
        $this->em->flush($author);
        $this->assertEquals(
            UnitOfWork::STATE_MANAGED,
            $this->em->getUnitOfWork()->getEntityState($author)
        );
        $this->em->remove($author);
        $this->assertEquals(
            UnitOfWork::STATE_REMOVED,
            $this->em->getUnitOfWork()->getEntityState($author)
        );
        $this->em->flush($author);
        $this->assertEquals(
            UnitOfWork::STATE_NEW,
            $this->em->getUnitOfWork()->getEntityState($author)
        );
    }

    public function testIdentityMap(): void
    {
        $country = $this->em->getRepository(Country::class)->findOneBy(['name' => 'Россия']);
        $country1 = $this->em->getRepository(Country::class)->findOneBy(['name' => 'Россия']);

        $this->assertSame($country, $country1);
    }
}
