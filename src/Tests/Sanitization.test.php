<?php

namespace Kernel\Test;

use PHPUnit\Framework\TestCase;
use function Kernel\Util\Sanitize\alphanum;
use function Kernel\Util\Sanitize\quotes;

class SanatizationTest extends TestCase
{

    public function testAlphanumericalSanitization()
    {
        $this->assertEquals(
            "a",
            alphanum("a@")
        );
    }

    public function testQuotedSanitization()
    {
        $this->assertEquals(
            "&quot;@&lt;&gt;",
            quotes("\"@<>")
        );
    }

}