<?php

namespace GreenBeans\Util;

class Random
{

    /**
     * Generates a cryptographically insecure pseudo random number
     * @param int $min
     * @param int $max
     * @return int
     */
    public static function unsafeInt(int $min = 0, int $max = 1): int
    {
        return rand($min, $max);
    }

    /**
     * Generates a cryptographically secure pseudo random number
     * @param int $min
     * @param int $max
     * @return int
     * @throws \Exception
     */
    public static function safeInt(int $min = 0, int $max = 1): int
    {
        return random_int($min, $max);
    }

    /**
     * Generates a cryptographically insecure pseudo random string
     * @param int $length
     * @return string
     */
    public static function unsafeString(int $length = 1): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Generates cryptographically secure pseudo random string
     * @param int $bytes
     * @return string
     * @throws \Exception
     */
    public static function safeString(int $bytes = 100): string
    {
        return bin2hex(self::bytes($bytes));
    }

    /**
     * Generates cryptographically secure pseudo random bytes
     * @param int $bytes
     * @return string
     * @throws \Exception
     */
    public static function bytes(int $bytes = 100): string
    {
        $randomBytes = null;
        if (function_exists("random_bytes")) {
            try {
                $randomBytes = random_bytes($bytes);
            } catch (\Exception $ex) {
                return openssl_random_pseudo_bytes($bytes);
            }
            return $randomBytes;
        }
        return openssl_random_pseudo_bytes($bytes);
    }

}