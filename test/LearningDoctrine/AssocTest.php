<?php

namespace EfTech\BookLibraryTest\LearningDoctrine;

use Doctrine\DBAL\Exception;
use EfTech\BookLibraryTest\LearningDoctrine\AssocTestEntities\Remove\Composition\Address;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM;

class AssocTest extends TestCase
{
    /**
     * Имя базы для тестов
     *
     * @var string
     */
    private string $testDbName = 'test_assoc_db';


    /**
     * Создание менеджера сущностей к конкретной базе данных
     *
     *
     * @param array $entityPaths
     * @param string|null $dbName
     * @return ORM\EntityManager
     * @throws ORM\ORMException
     */
    private static function createEntityManager(array $entityPaths = [], string $dbName = null): ORM\EntityManager
    {
        $connection = [
            'driver' => 'pdo_pgsql',
            'user' => 'postgres',
            'password' => 'Qwerty12',
            'host' => 'localhost',
            'port' => 5432
        ];

        if (null !== $dbName) {
            $connection['dbname'] = $dbName;
        }

        $configuration = ORM\Tools\Setup::createAnnotationMetadataConfiguration(
            $entityPaths,
            true,
            __DIR__ . '/../../var/cache/doctrine/proxy',
            null,
            false
        );

        return ORM\EntityManager::create($connection, $configuration);
    }

    /**
     * @throws ORM\ORMException
     * @throws Exception
     */
    protected function setUp(): void
    {
        $schemaManager = self::createEntityManager()->getConnection()->createSchemaManager();

        if (in_array($this->testDbName, $schemaManager->listDatabases())) {
            $schemaManager->dropDatabase($this->testDbName);
        }

        $schemaManager->createDatabase($this->testDbName);

        parent::setUp();
    }

    /**
     * Иллюстрация однонаправленной связи один к одному, когда владельцем связи выступает сущность пользователь
     *
     *
     * @throws ORM\Tools\ToolsException|ORM\ORMException
     * @throws Exception
     */
    public function testOneToOneUnidirectionalUserOwn(): void
    {
        $em = self::createEntityManager(
            [__DIR__ . '/AssocTestEntities/OneToOne/UnidirectionalUserOwn'],
            $this->testDbName
        );

        //Action
        $tool = new ORM\Tools\SchemaTool($em);
        $tool->createSchema($em->getMetadataFactory()->getAllMetadata());

        $this->assertEqualsCanonicalizing(
            ['users', 'address'],
            $em->getConnection()->createSchemaManager()->listTableNames()
        );
    }

    /**
     * Иллюстрация однонаправленной связи один к одному, когда владельцем связи выступает сущность адресс
     *
     *
     * @throws ORM\Tools\ToolsException|ORM\ORMException
     * @throws Exception
     */
    public function testOneToOneUnidirectionalAddressOwn(): void
    {
        $em = self::createEntityManager(
            [__DIR__ . '/AssocTestEntities/OneToOne/UnidirectionalAddressOwn'],
            $this->testDbName
        );

        //Action
        $tool = new ORM\Tools\SchemaTool($em);
        $tool->createSchema($em->getMetadataFactory()->getAllMetadata());

        $this->assertEqualsCanonicalizing(
            ['users', 'address'],
            $em->getConnection()->createSchemaManager()->listTableNames()
        );
    }


    /**
     * @throws ORM\Tools\ToolsException
     * @throws Exception
     * @throws ORM\ORMException
     */
    public function testOneToOneTwoUnidirectional(): void
    {
        $em = self::createEntityManager(
            [__DIR__ . '/AssocTestEntities/OneToOne/TwoUnidirectional'],
            $this->testDbName
        );

        //Action
        $tool = new ORM\Tools\SchemaTool($em);
        $tool->createSchema($em->getMetadataFactory()->getAllMetadata());

        $this->assertEqualsCanonicalizing(
            ['users', 'address'],
            $em->getConnection()->createSchemaManager()->listTableNames()
        );
    }

    /**
     * Иллюстрация двунаправленной связи один к одному, когда владельцем связи выступает сущность юзер
     *
     * @throws ORM\Tools\ToolsException
     * @throws Exception
     * @throws ORM\ORMException
     */
    public function testOneToOneBiDirectionalUserOwn(): void
    {
        $em = self::createEntityManager(
            [__DIR__ . '/AssocTestEntities/OneToOne/BiDirectionalUserOwn'],
            $this->testDbName
        );

        //Action
        $tool = new ORM\Tools\SchemaTool($em);
        $tool->createSchema($em->getMetadataFactory()->getAllMetadata());

        $this->assertEqualsCanonicalizing(
            ['users', 'address'],
            $em->getConnection()->createSchemaManager()->listTableNames()
        );
    }


    /**
     * Иллюстрация двунаправленной связи один к одному, когда владельцем связи выступает сущность адресс
     *
     * @throws ORM\Tools\ToolsException
     * @throws Exception
     * @throws ORM\ORMException
     */
    public function testOneToOneBiDirectionalAddressOwn(): void
    {
        $em = self::createEntityManager(
            [__DIR__ . '/AssocTestEntities/OneToOne/BiDirectionalAddressOwn'],
            $this->testDbName
        );

        //Action
        $tool = new ORM\Tools\SchemaTool($em);
        $tool->createSchema($em->getMetadataFactory()->getAllMetadata());

        $this->assertEqualsCanonicalizing(
            ['users', 'address'],
            $em->getConnection()->createSchemaManager()->listTableNames()
        );
    }

    /**
     * Иллюстрация двунаправленной связи один к одному, когда владельцем связи выступает сущность адресс
     *
     * @throws ORM\Tools\ToolsException
     * @throws ORM\ORMException
     */
    public function testOneToOneBiDirectionalUserOwnFlush(): void
    {
        $em = self::createEntityManager(
            [__DIR__ . '/AssocTestEntities/OneToOne/BiDirectionalUserOwnFlush'],
            $this->testDbName
        );

        //Action
        $tool = new ORM\Tools\SchemaTool($em);
        $tool->createSchema($em->getMetadataFactory()->getAllMetadata());



        $user1 = new AssocTestEntities\OneToOne\BiDirectionalUserOwnFlush\User();
        $address = new AssocTestEntities\OneToOne\BiDirectionalUserOwnFlush\Address();


        $user1->setAddress($address);
        $address->setUser($user1);

        $em->persist($user1);
        $em->persist($address);

        $em->flush();

        $user2 = new AssocTestEntities\OneToOne\BiDirectionalUserOwnFlush\User();
        $em->persist($user2);
        $em->flush();

        $address->setUser($user2);
        $em->flush();

        $em->refresh($user1);


        $this->assertEquals($address->getId(), $user1->getAddress()->getId());
    }


    /**
     * Иллюстрация двунаправленной связи один к одному, когда владельцем связи выступает сущность адресс
     *
     * @throws ORM\Tools\ToolsException
     * @throws ORM\ORMException
     */
    public function testBiDirectionalUserOwnFlush(): void
    {
        $em = self::createEntityManager(
            [__DIR__ . '/AssocTestEntities/OneToOne/BiDirectionalUserOwnFlush'],
            $this->testDbName
        );

        //Action
        $tool = new ORM\Tools\SchemaTool($em);
        $tool->createSchema($em->getMetadataFactory()->getAllMetadata());



        $user = new AssocTestEntities\OneToOne\BiDirectionalUserOwnFlush\User();
        $address = new AssocTestEntities\OneToOne\BiDirectionalUserOwnFlush\Address();


        $user->setAddress($address);
        $address->setUser($user);

        $em->persist($user);
        $em->persist($address);

        $em->flush();

        $address2 = new AssocTestEntities\OneToOne\BiDirectionalUserOwnFlush\Address();
        $em->persist($address2);
        $em->flush();

        $user->setAddress($address2);
        $em->flush();

        $em->refresh($user);


        $this->assertEquals($user->getAddress()->getId(),$address2->getId());
    }


    /**
     *
     *
     * @throws ORM\Tools\ToolsException
     * @throws ORM\ORMException
     */
    public function testManyToOneBidirectionalUserInverseSide(): void
    {
        $em = self::createEntityManager(
            [__DIR__ . '/AssocTestEntities/ManyToOne/Bidirectional'],
            $this->testDbName
        );

        $tool = new ORM\Tools\SchemaTool($em);
        $tool->createSchema($em->getMetadataFactory()->getAllMetadata());

        //Action
        $user = new AssocTestEntities\ManyToOne\Bidirectional\User();
        $address1 = new AssocTestEntities\ManyToOne\Bidirectional\Address();
        $address2 = new AssocTestEntities\ManyToOne\Bidirectional\Address();

        $user->getAddress()->add($address1);
        $user->getAddress()->add($address2);

        $em->persist($user);
        $em->persist($address1);
        $em->persist($address2);

        $em->flush();

        $em->refresh($user);

        $this->assertCount(0, $user->getAddress()->toArray());


    }


    /**
     *
     *
     * @throws ORM\Tools\ToolsException
     * @throws ORM\ORMException
     */
    public function testManyToOneBidirectionalUserOwnSide(): void
    {
        $em = self::createEntityManager(
            [__DIR__ . '/AssocTestEntities/ManyToOne/Bidirectional'],
            $this->testDbName
        );

        $tool = new ORM\Tools\SchemaTool($em);
        $tool->createSchema($em->getMetadataFactory()->getAllMetadata());

        //Action
        $user = new AssocTestEntities\ManyToOne\Bidirectional\User();
        $address1 = new AssocTestEntities\ManyToOne\Bidirectional\Address();
        $address2 = new AssocTestEntities\ManyToOne\Bidirectional\Address();

        $address1->setUser($user);
        $address2->setUser($user);

        $em->persist($user);
        $em->persist($address1);
        $em->persist($address2);

        $em->flush();

        $em->refresh($user);

        $this->assertCount(2, $user->getAddress()->toArray());


    }


    /**
     *
     *
     * @throws ORM\Tools\ToolsException
     * @throws ORM\ORMException
     */
    public function testConsistencyEntity(): void
    {
        $em = self::createEntityManager(
            [__DIR__ . '/AssocTestEntities/ManyToOne/BidirectionalProtected'],
            $this->testDbName
        );

        $tool = new ORM\Tools\SchemaTool($em);
        $tool->createSchema($em->getMetadataFactory()->getAllMetadata());

        //Action
        $user = new AssocTestEntities\ManyToOne\BidirectionalProtected\User();
        $address1 = new AssocTestEntities\ManyToOne\BidirectionalProtected\Address();
        $address2 = new AssocTestEntities\ManyToOne\BidirectionalProtected\Address();


        $address1->registerUser($user);
        $address2->registerUser($user);

        $em->persist($user);
        $em->persist($address1);
        $em->persist($address2);


        $expectedCountAddresses = count($user->getAddress());


        $em->flush();

        $em->refresh($user);

        $this->assertCount($expectedCountAddresses, $user->getAddress()->toArray());


    }

    public function testRemoveAggregation(): void
    {
        $em = self::createEntityManager(
            [__DIR__ . '/AssocTestEntities/Remove/Aggregation'],
            $this->testDbName
        );

        $tool = new ORM\Tools\SchemaTool($em);
        $tool->createSchema($em->getMetadataFactory()->getAllMetadata());

        //Action
        $user = new AssocTestEntities\Remove\Aggregation\User();
        $address1 = new AssocTestEntities\Remove\Aggregation\Address();
        $address2 = new AssocTestEntities\Remove\Aggregation\Address();


        $address1->registerUser($user);
        $address2->registerUser($user);

        $em->persist($user);
        $em->persist($address1);
        $em->persist($address2);

        $em->flush();

        $user->moveOutAddress($address1);
        $user->moveOutAddress($address2);

        $em->flush();

        $this->assertCount(0, $user->getAddress()->toArray());



    }

    public function testRemoveComposition(): void
    {
        $em = self::createEntityManager(
            [__DIR__ . '/AssocTestEntities/Remove/Composition'],
            $this->testDbName
        );

        $tool = new ORM\Tools\SchemaTool($em);
        $tool->createSchema($em->getMetadataFactory()->getAllMetadata());

        //Action
        $user = new AssocTestEntities\Remove\Composition\User();
        $address1 = new AssocTestEntities\Remove\Composition\Address();
        $address2 = new AssocTestEntities\Remove\Composition\Address();


        $address1->registerUser($user);
        $address2->registerUser($user);

        $em->persist($user);
        $em->persist($address1);
        $em->persist($address2);

        $em->flush();

        $user->moveOutAddress($address2);

        $em->flush();

        $this->assertCount(1, $user->getAddress()->toArray());

        $this->assertCount(
            1,
            $em->getRepository(AssocTestEntities\Remove\Composition\Address::class)->findAll()
        );

        $this->assertEquals(ORM\UnitOfWork::STATE_MANAGED, $em->getUnitOfWork()->getEntityState($address1));
        $this->assertEquals(ORM\UnitOfWork::STATE_NEW, $em->getUnitOfWork()->getEntityState($address2));

    }

    /**
     * @throws ORM\OptimisticLockException
     * @throws ORM\ORMException
     * @throws ORM\Tools\ToolsException
     */
    public function testCascadePersist(): void
    {
        $em = self::createEntityManager(
            [__DIR__ . '/AssocTestEntities/Cascade/Persist'],
            $this->testDbName
        );

        $tool = new ORM\Tools\SchemaTool($em);
        $tool->createSchema($em->getMetadataFactory()->getAllMetadata());

        //Action
        $user = new AssocTestEntities\Cascade\Persist\User();
        $address1 = new AssocTestEntities\Cascade\Persist\Address();
        $address2 = new AssocTestEntities\Cascade\Persist\Address();


        $address1->registerUser($user);
        $address2->registerUser($user);

        $em->persist($user);

        $em->flush();

        $this->assertCount(2, $user->getAddress()->toArray());

        $this->assertCount(
            2,
            $em->getRepository(AssocTestEntities\Cascade\Persist\Address::class)->findAll()
        );

        $this->assertEquals(ORM\UnitOfWork::STATE_MANAGED, $em->getUnitOfWork()->getEntityState($address1));
        $this->assertEquals(ORM\UnitOfWork::STATE_MANAGED, $em->getUnitOfWork()->getEntityState($address2));

    }


    /**
     * @throws ORM\OptimisticLockException
     * @throws ORM\ORMException
     * @throws ORM\Tools\ToolsException
     */
    public function testCascadeRemove(): void
    {
        $em = self::createEntityManager(
            [__DIR__ . '/AssocTestEntities/Cascade/Remove'],
            $this->testDbName
        );

        $tool = new ORM\Tools\SchemaTool($em);
        $tool->createSchema($em->getMetadataFactory()->getAllMetadata());

        //Action
        $user = new AssocTestEntities\Cascade\Remove\User();
        $address1 = new AssocTestEntities\Cascade\Remove\Address();
        $address2 = new AssocTestEntities\Cascade\Remove\Address();


        $address1->registerUser($user);
        $address2->registerUser($user);

        $em->persist($user);

        $em->flush();

        $em->remove($user);

        $this->assertEquals(ORM\UnitOfWork::STATE_REMOVED, $em->getUnitOfWork()->getEntityState($address1));
        $this->assertEquals(ORM\UnitOfWork::STATE_REMOVED, $em->getUnitOfWork()->getEntityState($address2));
        $this->assertEquals(ORM\UnitOfWork::STATE_REMOVED, $em->getUnitOfWork()->getEntityState($user));

        $em->flush();

    }

}
