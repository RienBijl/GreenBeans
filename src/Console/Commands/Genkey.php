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
        $key = Encryption::getSafeKey();

        file_put_contents(Base::get() . '/storage/secret/.key.crypto', $key);
        parent::success("Generated new application key");
        file_put_contents(Base::get() . '/storage/secret/.initialization_vector.crypto', Random::bytes(16));
        parent::success("Generated new initialization vector");
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