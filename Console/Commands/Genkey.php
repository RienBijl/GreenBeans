<?php

namespace GreenBeans\Console\Commands;

use GreenBeans\Console\Command;
use GreenBeans\Util\Base;
use GreenBeans\Util\Encryption;

class Genkey extends Command
{

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function run(array $args): void
    {
        $key = Encryption::getSafeKey();

        file_put_contents(Base::get() . '/.key', $key);
        parent::success("Generated new application key");
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public static function invoke(array $args): void
    {
        (new self())->run($args);
    }
}