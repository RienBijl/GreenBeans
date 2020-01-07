<?php

namespace Kernel\Util;

class Stopwatch
{

    /**
     * Starting time
     * @var int
     */
    private int $start;

    /**
     * Stopwatch constructor.
     */
    public function __construct()
    {
        $this->start = self::getTime();
    }

    /**
     * Get the stop time in nanoseconds
     * @return int
     */
    public function stopAsNano(): int
    {
        return self::getTime() - $this->start;
    }

    /**
     * Get the stop time in microseconds
     * @return int
     */
    public function stopAsMicro(): int
    {
        return 1e-3 * (self::getTime() - $this->start);
    }

    /**
     * Get the stop time in milliseconds
     * @return int
     */
    public function stopAsMilli(): int
    {
        return 1e-6 * (self::getTime() - $this->start);
    }

    /**
     * Get the stop time in seconds
     * @return int
     */
    public function stopAsSecond(): int
    {
        return 1e-9 * (self::getTime() - $this->start);
    }

    /**
     * Get the accurate time
     * @return int
     * @author Bas Milius <bas@mili.us>
     */
    private static function getTime(): int
    {
        if (function_exists('hrtime')) {
            return hrtime(true);
        } else {
            return (int) microtime(true) * 1e9;
        }
    }

}