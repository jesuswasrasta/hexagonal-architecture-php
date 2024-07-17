<?php
declare(strict_types=1);

namespace App\Unit\Infrastructure;

use App\Domain\Users\SubscriptionDate;
use App\Domain\Users\Username;
use App\Domain\Users\Users;
use App\Domain\Users\UsersId;
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
        $this->usersId = new UsersId("d9208ab6-6402-49e9-a61d-111111111111");
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
        $users->add(new Username("Giacomo Dino"), new SubscriptionDate(new \DateTime("2024-07-17 00:00:00.000000")));
        $this->fileUsersRepository->save($users);

        $users = $this->fileUsersRepository->getById($this->usersId);

        $this->assertInstanceOf(Users::class, $users);
        $this->assertEquals($this->usersId, $users->id());
    }
}
