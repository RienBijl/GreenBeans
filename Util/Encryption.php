<?php

namespace GreenBeans\Util;

use GreenBeans\Exceptions\EncryptionException;

class Encryption
{

    /**
     * Encrypt a piece of information
     * @param string $information
     * @param string $key
     * @param int $iv
     * @return string
     * @throws EncryptionException
     */
    public static function encrypt(string $information, string $key, int $iv = 16): string
    {
        if (self::getEntropy($key) < 4) {
            throw new EncryptionException("Insufficient entropy in key, try using getSafeKey() or the application key");
        }
        return openssl_encrypt(
            self::pksc7Pad($information, 16),
            'AES-256-CBC',
            $key,
            0,
            $iv
        );
    }

    /**
     * Decrypt a piece of information
     * @param string $information
     * @param string $key
     * @param int $iv
     * @return string
     */
    public static function decrypt(string $information, string $key, int $iv = 16): string
    {
        return self::pksc7Unpad(openssl_decrypt(
            $information,
            'AES-256-CBC',
            $key,
            0,
            $iv
        ));
    }

    /**
     * Generate a safe key
     * @throws \Exception
     * @return string
     */
    public static function getSafeKey(): string
    {
        do {
            $safeKey = Random::safeString(32 / 2);
        } while (self::getEntropy($safeKey) < 4);
        return $safeKey;
    }

    /**
     * Get the entropy of a given piece of information
     * @param string $information
     * @return float
     */
    public static function getEntropy(string $information): double
    {
        $entropy = 0;
        $size = strlen($information);
        foreach (count_chars($information, 1) as $v) {
            $p = $v / $size;
            $entropy -= $p * log($p) / log(2);
        }
        return $entropy;
    }

    /**
     * Pad a string
     * @param $data
     * @param $size
     * @return string
     */
    private static function pksc7Pad(string $data, int $size): string
    {
        $length = $size - strlen($data) % $size;
        return $data . str_repeat(chr($length), $length);
    }

    /**
     * Unpad a string
     * @param $data
     * @return string
     */
    private static function pksc7Unpad($data): string
    {
        return substr($data, 0, -ord($data[strlen($data) - 1]));
    }

}