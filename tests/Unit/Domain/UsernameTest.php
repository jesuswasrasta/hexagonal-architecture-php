<?php
declare(strict_types=1);

namespace App\Unit\Domain;

use App\Domain\Users\Username;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Domain\Users\Username
 */
final class UsernameTest extends TestCase
{
    /**
     * @covers \App\Domain\Users\Username::equals
     */
    public function testEqualsSuccess()
    {
        $username = new Username('sameUsername');
        $anotherUsername = new Username('sameUsername');
        $this->assertTrue($username->equals($anotherUsername));
    }

    /**
     * @covers \App\Domain\Users\Username::equals
     */
    public function testEqualsFail()
    {
        $username = new Username('oneUsername');
        $anotherUsername = new Username('anotherUsername');
        $this->assertFalse($username->equals($anotherUsername));
    }
}
