<?php

namespace GreenBeans\Console\Commands;

use GreenBeans\Console\Command;
use GreenBeans\Util\Base;

class Serve extends Command
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

        chdir(Base::get() . '/public/');

        $this->success("Starting Frappuccino development server");
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