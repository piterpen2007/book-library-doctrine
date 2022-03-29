<?php

namespace EfTech\BookLibraryTest\LearningDoctrine;

use Doctrine\DBAL\Exception;
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




}
