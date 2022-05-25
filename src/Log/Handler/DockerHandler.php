<?php

namespace App\Log\Handler;

use Monolog\Formatter\LineFormatter;
use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class DockerHandler extends AbstractProcessingHandler
{
    /** @var resource|false */
    private $resource;
    private string $command;

    public function __construct(
        int $processId = 1,
        int $fileDescriptor = 2,
        mixed $level = Logger::DEBUG,
        bool $bubble = true,
        FormatterInterface $formatter = null
    ) {
        $this->command = sprintf('cat - >> /proc/%d/fd/%d', $processId, $fileDescriptor);

        parent::__construct($level, $bubble);

        if (null !== $formatter) {
            $this->setFormatter($formatter);
        }
    }

    public function close(): void
    {
        if (is_resource($this->resource)) {
            pclose($this->resource);
        }

        parent::close();
    }

    protected function write(array $record): void
    {
        if (!is_resource($this->resource)) {
            $this->resource = popen($this->command, 'w');
        }

        if ($this->resource === false) {
            throw new \Exception("Could not create stream resource.");
        }

        fwrite($this->resource, (string)$record['formatted']);
    }

    protected function getDefaultFormatter(): FormatterInterface
    {
        return new LineFormatter();
    }
}
