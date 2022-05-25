<?php

namespace App;

use App\Log\Handler\DockerHandler;
use League\BooBoo\BooBoo;
use League\BooBoo\Formatter\HtmlFormatter;
use League\BooBoo\Formatter\NullFormatter;
use League\BooBoo\Handler\LogHandler;
use Monolog\Logger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/** Simple class used to boot the application and provide config, DI, Logging and any other required setup  */
final class Kernel
{
    private DependencyContainer $container;
    private array $configuration = [];

    public function boot(): void
    {
        $configuration = $this->getConfiguration();
        $this->container = new DependencyContainer($configuration);
        $booboo = new BooBoo([]);

        $logger = new Logger('error_log');
        $logger->pushHandler(new DockerHandler());
        $booboo->pushHandler(new LogHandler($logger));

        if ($configuration['env'] === 'dev') {
            $booboo->pushFormatter(new HtmlFormatter);
        } else {
            // To display pretty errors implement new formatter
            $booboo->pushFormatter(new NullFormatter);
        }
        $booboo->register();
    }

    public function getContaner(): DependencyContainer
    {
        return $this->container;
    }

    private function getConfiguration(): array
    {
        if ($this->configuration === []) {
            $this->configuration = require_once __DIR__ . "/../config/configuration.php";
        }

        return $this->configuration;
    }

    public function handle(Request $request): Response
    {
        return $this->getContaner()->getController()->__invoke($request);
    }
}
