<?php

namespace GreenBeans\Console;

use App\Commands\CommandRegistry;
use GreenBeans\Util\ANSIColor;

class Execution
{

    /**
     * @var array|null
     */
    private $matchers = null;

    /**
     * @var string
     */
    public $basedir = __DIR__;

    /**
     * Execution constructor.
     * @param $args
     * @param $basedir
     */
    public function __construct(string $args, string $basedir)
    {
        if (php_sapi_name() === 'cli') {
            set_exception_handler([$this, 'catchException']);
        }

        $this->basedir = $basedir;
        $args ??= [];

        $this->matchers = $this->getMatchers();
        array_shift($args);
        $this->match($args);
    }

    /**
     * Match set of arguments to command
     * @param $args
     */
    private function match(array $args): void
    {
        $masterMatch = false;
        foreach ($this->matchers as $matcherKey => $matcherVal) {
            $matcher = explode("|", $matcherKey);
            $count = 0;
            $match = true;
            foreach ($matcher as $matchPiece) {
                if (!(array_key_exists($count, $args) && ($args[$count] === $matchPiece || $matchPiece === "{var}"))) {
                    $match = false;
                }
                $count++;
            }
            if ($match) {
                $masterMatch = true;
                $this->invoke($matcherVal, $args);
                break;
            }
        }
        if (!$masterMatch) {
            echo "\033[1;37m" .  "\033[41m" . 'Error: ' . 'Command not found in registry' . "\033[0m" . PHP_EOL;
        }
    }

    /**
     * Invoke new command
     *
     * @param $class
     * @param $args
     */
    private function invoke(string $class, array $args): void
    {
        ($class)::invoke($args);
    }

    /**
     * Get all the appropriate matchers
     * @return array
     */
    private function getMatchers(): array
    {
        return $this->matchers ?? array_merge(KernelCommandRegistry::$registry, CommandRegistry::$registry);
    }

    /**
     * Handle uncatched exceptions
     * @param $ex
     */
    public function catchException($ex): void
    {
        echo ANSIColor::parse($ex, ANSIColor::FG_WHITE, ANSIColor::BG_CYAN) . PHP_EOL;
    }
}
