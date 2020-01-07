<?php

namespace GreenBeans\Console\Commands;

use GreenBeans\Console\Command;
use GreenBeans\Util\Base;
use GreenBeans\Util\Encryption;
use GreenBeans\Util\Random;

class Genkey extends Command
{

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function run(array $args): void
    {
        do {
            $safeString = Random::safeString(32 / 2);
        } while (Encryption::getEntropy($safeString) < 4);

        file_put_contents(Base::get() . '/.key', $safeString);
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