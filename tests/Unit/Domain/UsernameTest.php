<?php
declare(strict_types=1);

namespace App\Unit\Domain;

use App\Domain\Users\Username;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(\App\Domain\Users\Username::class)]
final class UsernameTest extends TestCase
{
    public function testEqualsSuccess()
    {
        $username = new Username('sameUsername');
        $anotherUsername = new Username('sameUsername');
        $this->assertTrue($username->equals($anotherUsername));
    }

    public function testEqualsFail()
    {
        $username = new Username('oneUsername');
        $anotherUsername = new Username('anotherUsername');
        $this->assertFalse($username->equals($anotherUsername));
    }
}
