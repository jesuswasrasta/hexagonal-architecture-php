<?php

namespace App\Unit\Infrastructure;

use App\Domain\Users;
use App\Infrastructure\JsonUsersRepository;
use App\Infrastructure\JsonUsersRepositoryException;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Infrastructure\JsonUsersRepository
 */
class JsonUsersRepositoryTest extends TestCase
{
    /**
     * @var string
     */
    protected string $filename;

    protected function setUp(): void
    {
        parent::setUp();

        $this->filename = sys_get_temp_dir() . '/users.json';
    }

    protected function tearDown(): void
    {
        if (file_exists($this->filename)){
            unlink($this->filename);
        }

        parent::tearDown();
    }

    /**
     * @covers ::readUsers
     */
    public function test_read_users_successful_case(): void
    {
        $users = ['Username1','Username2','Username3'];
        file_put_contents($this->filename, json_encode($users));
        $jsonRepo = new JsonUsersRepository($this->filename);

        $result = $jsonRepo->readUsers()->toStringArray();

        $this->assertEquals($users, $result);
    }

    public function test_read_users_with_empty_file(): void
    {
        $jsonRepo = new JsonUsersRepository($this->filename);
        $result = $jsonRepo->readUsers();
        $this->assertTrue(is_a($result, Users::class));
    }

    public function test_read_users_with_invalid_json(): void
    {
        file_put_contents($this->filename, "{invalidJson: 'test'}");
        $this->expectException(JsonUsersRepositoryException::class);
        $jsonRepo = new JsonUsersRepository($this->filename);
        $jsonRepo->readUsers();
    }

    /**
     * @covers ::readUsers
     */
    public function testReadUsersInvalidJson(): void
    {
        file_put_contents($this->filename, "invalidJson");
        $jsonRepo = new JsonUsersRepository($this->filename);

        $this->expectException(JsonUsersRepositoryException::class);

        $jsonRepo->readUsers();
    }
}
