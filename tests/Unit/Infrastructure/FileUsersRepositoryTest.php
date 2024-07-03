<?php

namespace App\Unit\Infrastructure;

use App\Domain\Username;
use App\Domain\Users;
use App\Domain\UsersId;
use App\Infrastructure\FileUsersRepository;
use PHPUnit\Framework\TestCase;

class FileUsersRepositoryTest extends TestCase
{
    private FileUsersRepository $fileUsersRepository;

    protected string $filename;
    private UsersId $usersId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fileUsersRepository = new FileUsersRepository();
        $this->usersId = UsersId::random();
        $this->filename = $this->usersId->value() . ".txt";
    }

    protected function tearDown(): void
    {
        if (file_exists($this->filename)) {
            unlink($this->filename);
        }

        parent::tearDown();
    }

    public function testGetByIdWithEmptyFile(): void
    {
        $users = $this->fileUsersRepository->getById($this->usersId);

        $this->assertInstanceOf(Users::class, $users);
        $this->assertEmpty($users->toStringArray());
    }

    public function testGetByIdWithPopulatedFile(): void
    {
        $users = Users::create($this->usersId);
        $users->add(new Username("Giacomo Dino"));
        $this->fileUsersRepository->save($users);

        $users = $this->fileUsersRepository->getById($this->usersId);

        $this->assertInstanceOf(Users::class, $users);
        $this->assertTrue(in_array('Giacomo Dino', $users->toStringArray()));
    }
}
