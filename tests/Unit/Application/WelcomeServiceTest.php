<?php

namespace App\Unit\Application;

use App\Application\UsersRepositoryInterface;
use App\Application\WelcomeService;
use App\Domain\Username;
use App\Domain\Users;
use App\Domain\UsersId;
use PHPUnit\Framework\TestCase;

/**
 * Class WelcomeServiceTest
 * @package App\Application
 * This class is for testing WelcomeService
 */
class WelcomeServiceTest extends TestCase
{
    private UsersRepositoryInterface $mockedUsersRepository;

    public function setUp(): void
    {
        $this->mockedUsersRepository = $this->createMock(UsersRepositoryInterface::class);
    }

    /**
     * Test welcomeUser method when adding a new user
     */
    public function testWelcomeUserAddNewUser() : void
    {
        $users = Users::create(UsersId::random());
        $this->mockedUsersRepository->method('readUsers')->willReturn($users);
        $this->mockedUsersRepository
            ->expects($this->once())
            ->method('writeUsers');
        $service = new WelcomeService($this->mockedUsersRepository);
        $username = 'New User';
        $this->assertEquals("Welcome, " . $username . "!", $service->welcomeUser($username));
    }

    /**
     * Test welcomeUser method when the user is already present
     */
    public function testWelcomeUserExistingUser() : void
    {
        $username = 'Existing User';
        $users = Users::create(UsersId::random());
        $users->add(new Username('Existing User'));
        $this->mockedUsersRepository->method('readUsers')->willReturn($users);
        $service = new WelcomeService($this->mockedUsersRepository);

        $this->assertEquals("Welcome back, " . $username . "!", $service->welcomeUser($username));
    }
}
