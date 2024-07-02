<?php

namespace App\Unit\Infrastructure;

use App\Domain\Users;
use App\Infrastructure\FileUsersRepository;
use PHPUnit\Framework\TestCase;

class FileUsersRepositoryTest extends TestCase
{
    private FileUsersRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $filename = tempnam(sys_get_temp_dir(), 'FileUsersRepositoryTest');
        file_put_contents($filename, implode("\n", ['User1', 'User2', 'User3']));
        $this->repository = new FileUsersRepository($filename);
    }

    public function testReadUsers(): void
    {
        $users = $this->repository->readUsers();

        $this->assertInstanceOf(Users::class, $users);
        $this->assertNotEmpty($users->toStringArray());
    }
}
