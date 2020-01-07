<?php

namespace GreenBeans\Console\Commands;

use GreenBeans\Console\Command;

class Serveroot extends Command
{

    private string $host = "127.0.0.1:8080";

    /**
     * @inheritDoc
     */
    public function run(array $args): void
    {
        if (isset($args[1])) {
            $this->host = escapeshellarg($args[1]);
        }

        chdir(__DIR__ . '/../../../');

        $this->success("Starting Frappuccino development server");
        $this->warn("Server is facing root directory");

        passthru(PHP_BINARY . " -S {$this->host} 2>&1");
    }

    /**
     * @inheritDoc
     */
    public static function invoke(array $args): void
    {
        (new self())->run($args);
    }
}