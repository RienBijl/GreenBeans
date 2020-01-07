<?php

namespace GreenBeans;

/**
 * Class Base
 *
 * @package GreenBeans
 */
class Base
{

    /**
     * @var string
     */
    private static string $base = __DIR__;

    /**
     * Set the base path
     * @param string $base
     * @return void
     */
    public static function set(string $base): void
    {
        self::$base = $base;
    }

    /**
     * Get the base path
     * @return string
     */
    public static function get(): string
    {
        return self::$base;
    }


}