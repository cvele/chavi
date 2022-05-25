<?php declare(strict_types=1);
namespace App;

use App\Controller\Controller;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;

/** Very simple DI container, fit for purpose.
 * In an actual project RFC compatible package would be used */
class DependencyContainer
{
    private array $instances = [];
    private array $configuration = [];

    public function __construct(array $configuration = [])
    {
        $this->configuration = $configuration;
    }

    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    public function getEntityManager(): EntityManagerInterface
    {
        if (isset($this->instances['entityManager']) && $this->instances['entityManager'] instanceof EntityManagerInterface) {
            return $this->instances['entityManager'];
        }

        $config = ORMSetup::createAttributeMetadataConfiguration(
            [ __DIR__ . DIRECTORY_SEPARATOR . "Entity"],
            getenv('ENV') === 'prod'?true:false
        );
        
        if (!isset($this->getConfiguration()['db']) || !\is_array($this->getConfiguration()['db'])) {
            throw new \Exception("Could not find database configuration");
        }

        $connectionParameters = [
            'driver'   => $this->getConfiguration()['db']['driver'],
            'host'     => $this->getConfiguration()['db']['hostname'],
            'user'     => $this->getConfiguration()['db']['username'],
            'password' => $this->getConfiguration()['db']['password'],
            'dbname'   => $this->getConfiguration()['db']['name'],
        ];

        if (getenv('ENV') === 'test') {
            $connectionParameters['dbname'] = getenv('ENV') . '_' . getenv('DB_NAME');
        }
        return $this->instances['entityManager'] = EntityManager::create($connectionParameters, $config);
    }

    public function getController(): Controller
    {
        if (isset($this->instances['controller']) && $this->instances['controller'] instanceof Controller) {
            return $this->instances['controller'];
        }

        return $this->instances['controller'] = new Controller(
            $this->getEntityManager()->getRepository(User::class),
        );
    }
}
