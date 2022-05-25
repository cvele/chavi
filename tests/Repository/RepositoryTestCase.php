<?php
namespace Tests\Repository;

use App\Kernel;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\TestCase;

class RepositoryTestCase extends TestCase
{
    private static Kernel $kernel;

    public function setUp(): void
    {
        parent::setUp();
    }

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::$kernel = new Kernel();
        self::$kernel->boot();
        $em = self::getEntityManager();
        $params = $em->getConnection()->getParams();
        $name = $params['dbname'];
        unset($params['dbname'], $params['path'], $params['url']);
        $tmpConnection = DriverManager::getConnection($params);
        $tmpConnection->connect();
        $schemaManager = $tmpConnection->createSchemaManager();
        $databaseExists = in_array($name, $schemaManager->listDatabases());
        if ($databaseExists) {
            $schemaManager->dropDatabase($name);
        }
        $schemaManager->createDatabase($name);
        $em->getConnection()->createSchemaManager()->createSchema();
        $schemaTool = new SchemaTool($em);
        $metadata = $em->getMetadataFactory()->getAllMetadata();
        $schemaTool->createSchema($metadata);
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();
        $params = self::getEntityManager()->getConnection()->getParams();
        $name = $params['dbname'];
        $schemaManager = self::getEntityManager()->getConnection()->createSchemaManager();
        $schemaManager->dropDatabase($name);
    }

    protected static function getEntityManager(): EntityManager
    {
        return self::$kernel->getContaner()->getEntityManager();
    }
}
