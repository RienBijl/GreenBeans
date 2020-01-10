<?php

namespace GreenBeans\Console;

use GreenBeans\Util\ANSIColor;

abstract class Command
{

    /**
     * Run te command
     * @param $args
     * @return mixed
     */
    abstract public function run(array $args): void;

    /**
     * Invoke the command.
     * @param $args
     */
    abstract public static function invoke(array $args): void;

    /**
     * Print a message to the console
     * @param $message
     */
    public function msg(string $message): void
    {
        echo $message . PHP_EOL;
    }

    /**
     * Print an error to the console
     * @param string $message
     */
    public function error(string $message): void
    {
        echo ANSIColor::parse("Error: " . $message, ANSIColor::FG_WHITE, ANSIColor::BG_RED) . PHP_EOL;
    }

    /**
     * Print a warning to the console
     * @param string $message
     */
    public function warn(string $message): void
    {
        echo ANSIColor::parse("Warning: " . $message, ANSIColor::FG_WHITE, ANSIColor::BG_YELLOW) . PHP_EOL;
    }

    /**
     * Print a success message to the console
     * @param string $message
     */
    public function success(string $message): void
    {
        echo ANSIColor::parse("Success: " . $message, ANSIColor::FG_WHITE, ANSIColor::BG_GREEN) . PHP_EOL;
    }
}