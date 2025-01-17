<?php
declare(strict_types=1);

namespace App\Unit\Infrastructure;

use App\Domain\Users\SubscriptionDate;
use App\Domain\Users\Username;
use App\Domain\Users\Users;
use App\Domain\Users\UsersId;
use App\Infrastructure\JsonUsersRepository;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Infrastructure\JsonUsersRepository
 */
class JsonUsersRepositoryTest extends TestCase
{
    private JsonUsersRepository $jsonUsersRepository;
    protected string $filename;
    private UsersId $usersId;


    protected function setUp(): void
    {
        parent::setUp();

        $this->jsonUsersRepository = new JsonUsersRepository();
        $this->usersId = UsersId::random();
        $this->filename = $this->usersId->value() . ".json";
    }

    protected function tearDown(): void
    {
        if (file_exists($this->filename)) {
            unlink($this->filename);
        }

        parent::tearDown();
    }

    /**
     * @covers ::getById
     */
    public function testGetByIdWhenFileDoesNotExist(): void
    {
        $users = $this->jsonUsersRepository->getById($this->usersId);

        $this->assertInstanceOf(Users::class, $users);
        $this->assertEmpty($users->toStringArray());
    }

    /**
     * @covers ::getById
     */
    public function testGetByIdWithValidJsonFile(): void
    {
        $users = Users::create($this->usersId);
        $users->add(new Username("Giacomo Dino"), new SubscriptionDate(new \DateTime("2024-07-17")));
        $this->jsonUsersRepository->save($users);

        $result = $this->jsonUsersRepository->getById($this->usersId);

        $this->assertInstanceOf(Users::class, $users);
        $this->assertEquals($users, $result);
    }
}
