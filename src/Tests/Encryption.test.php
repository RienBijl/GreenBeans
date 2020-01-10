<?php

namespace GreenBeans\Tests;

use GreenBeans\Exceptions\EncryptionException;
use GreenBeans\Util\Encryption as GBEncryption;
use PHPUnit\Framework\TestCase;

class Encryption extends TestCase
{

    /**
     * @throws EncryptionException
     */
    public function testSuccessfulEncryptDecrypt()
    {
        $information = "aaBBccDD@1:,";
        $encrypted = GBEncryption::encrypt($information);

        $this->assertNotEquals($encrypted, $information);
        $this->assertEquals(GBEncryption::decrypt($information), $information);
    }

    /**
     * @throws EncryptionException
     */
    public function testSuccessfulHmac()
    {
        $first = GBEncryption::hmac("aaBBccDD@1;,");
        $second = GBEncryption::hmac("AAbbCCdd@1;,");
        $third = GBEncryption::hmac("aaBBccDD@1;,");

        $this->assertTrue(
            GBEncryption::validate($first, $third)
        );

        $this->assertFalse(
            GBEncryption::validate($first, $second)
        );

        $this->assertFalse(
            GBEncryption::validate($third, $second)
        );
    }

}
