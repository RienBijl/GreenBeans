<?php

namespace Kernel\Util;

/**
 * Class ANSIColor
 * @package Kernel\Util
 * @author Rien Bijl <me at rienbijl.nl>
 */
class ANSIColor
{

    /**
     * Color a string
     * @param $message
     * @param $foreground
     * @param $background
     * @return string
     */
    public static function parse($message, $foreground, $background): string
    {
        $colorMsg = '';

        if ($background !== self::NONE) {
            $colorMsg = "\033[" . $background . "m";
        }

        if ($foreground !== self::NONE) {
            $colorMsg .= "\033[" . $foreground . "m";
        }

        $colorMsg .= $message . "\033[0m";

        return $colorMsg;
    }

    public const NONE = 0;
    public const FG_BLACK = '1;30';
    public const FG_DARK_GRAY = '1;30';
    public const FG_BLUE = '0;34';
    public const FG_LIGHT_BLUE = '1;34';
    public const FG_GREEN = '0;32';
    public const FG_LIGHT_GREEN = '1;32';
    public const FG_CYAN = '0;36';
    public const FG_RED =  '0;31';
    public const FG_LIGHT_RED = '1;31';
    public const FG_PURPLE = '0;35';
    public const FG_LIGHT_PURPLE = '1;35';
    public const FG_BROWN = '0;33';
    public const FG_YELLOW = '1;33';
    public const FG_LIGHT_GRAY = '0;37';
    public const FG_WHITE = '1;37';
    public const BG_BLACK = '40';
    public const BG_RED = '41';
    public const BG_GREEN = '42';
    public const BG_YELLOW = '43';
    public const BG_BLUE = '44';
    public const BG_MAGENTA = '45';
    public const BG_CYAN = '46';
    public const BG_LIGHT_GRAY = '47';

}
