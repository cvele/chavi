<?php
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;

require dirname(__DIR__).'/src/bootstrap.php';
$entityManager = getEntityManager(getenv('ENV')==='prod'?false:true);
return ConsoleRunner::run(
    new SingleManagerProvider($entityManager),
    []
);
