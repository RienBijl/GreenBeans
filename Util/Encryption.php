<?php

namespace GreenBeans\Util;

use GreenBeans\Exceptions\EncryptionException;

class Encryption
{

    /**
     * Encrypt a piece of information
     * @param string $information
     * @param string $key
     * @param string $iv
     * @return string
     * @throws EncryptionException
     * @throws \Exception
     */
    public static function encrypt(string $information, string $key = null, string $iv = null): string
    {
        if ($key === null) {
            $key = static::getAppKey();
        }
        if ($iv === null) {
            $iv = static::getAppIV();
        }
        if (self::getEntropy($key) < 4) {
            throw new EncryptionException("Insufficient entropy in key, try using getSafeKey() or the application key");
        }
        return openssl_encrypt(
            self::pksc7Pad($information . uniqid(), 16),
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
     * @param string $iv
     * @return string
     * @throws \Exception
     */
    public static function decrypt(string $information, string $key = null, string $iv = null): string
    {
        if ($key === null) {
            $key = static::getAppKey();
        }
        if ($iv === null) {
            $iv = static::getAppIV();
        }
        return substr(self::pksc7Unpad(openssl_decrypt(
            $information,
            'AES-256-CBC',
            $key,
            0,
            $iv
        )), -13);
    }

    /**
     * Get the application key
     * @return string|null
     * @throws EncryptionException
     */
    public static function getAppKey(): ?string
    {
        if (!file_exists(Base::get() . "\.key.crypto")) {
            throw new EncryptionException("Installation does not have valid application key, run `php beans genkey`");
        } else {
            return file_get_contents(Base::get() . ".\key.crypto");
        }
    }

    /**
     * Get the application initialization vector
     * @return string|null
     * @throws EncryptionException
     */
    public static function getAppIV(): ?string
    {
        if (!file_exists(Base::get() . "\.initialization_vector.crypto")) {
            throw new EncryptionException("Installation does not have valid application IV, run `php beans genkey`");
        } else {
            return file_get_contents(Base::get() . "\.initialization_vector.crypto");
        }
    }

    /**
     * Generate a safe key
     * @throws \Exception
     * @return string
     */
    public static function getSafeKey(): string
    {
        do {
            $safeKey = Random::safeString(32);
        } while (self::getEntropy($safeKey) < 4);
        return $safeKey;
    }

    /**
     * Get the entropy of a given piece of information
     * @param string $information
     * @return float
     */
    public static function getEntropy(string $information): float
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