<?php
namespace App\Unit\Application;
use App\Application\WelcomeService;
use App\Domain\Username;
use App\Domain\UsersId;
use App\Infrastructure\FileUsersRepository;
use PHPUnit\Framework\TestCase;

/**
 * Class WelcomeServiceTest
 * @package App\Application
 * @description This class tests the WelcomeService class, focusing particularly on the welcomeUser method.
 */
class WelcomeServiceTest extends TestCase
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

    /**
     * Test welcomeUser method when adding a new user
     * @test
     *
     * This test case checks if the 'welcomeUser()' method creates a new user,
     * saves the user to repository and returns a welcome message for new user.
     */
    public function it_welcomes_and_adds_new_user() : void
    {
        // Uso di proposito un FileUsersRepository e non un mock

        // Ocio! 👀
        // Questo test fallisce se non cancelli il file txt generato dall'esecuzione dell'app da riga di comando...
        // Sì, lo so, non è bello avere "brittle tests"...
        // Ma per adesso mi sta bene così 😉

        $username = new Username("Lampa Dario");
        $service = new WelcomeService($this->fileUsersRepository);
        $this->assertEquals("Welcome, " . $username . "!", $service->welcomeUser($username));
    }

    /**
     * Test that the existing user is welcomed correctly.
     *
     * @return void
     */
    public function it_welcomes_existing_user() : void
    {
        // Uso di proposito un FileUsersRepository e non un mock

        $users = $this->fileUsersRepository->getById($this->usersId);
        $username = new Username("Lampa Dario");
        $users->add($username);
        $this->fileUsersRepository->save($users);
        $service = new WelcomeService($this->fileUsersRepository);
        $this->assertEquals("Welcome back, " . $username . "!", $service->welcomeUser($username));
    }
}
