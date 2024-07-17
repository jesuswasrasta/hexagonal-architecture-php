<?php
declare(strict_types=1);

namespace App\Unit\Application;
use App\Application\WelcomeService;
use App\Domain\Users\SubscriptionDate;
use App\Domain\Users\Username;
use App\Domain\Users\UsersId;
use App\Infrastructure\FileUsersRepository;
use DateTime;
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

        $this->usersId = UsersId::random();
        $this->filename = $this->usersId->value() . ".txt";
        $this->fileUsersRepository = new FileUsersRepository();

        $this->deleteFile($this->filename);
    }

    protected function tearDown(): void
    {
        $this->deleteFile($this->filename);
        parent::tearDown();
    }

    /**
     * Test welcomeUser method when adding a new user
     * @test
     *
     * This test case checks if the 'welcomeUser()' method creates a new user,
     * saves the user to repository and returns a welcome message for new user.
     */
    public function it_welcomes_and_adds_new_user(): void
    {
        // Uso di proposito un FileUsersRepository e non un mock

        // Ocio! 👀
        // Questo test fallisce se non cancelli il file txt generato dall'esecuzione dell'app da riga di comando...
        // Sì, lo so, non è bello avere "brittle tests"...
        // Ma per adesso mi sta bene così 😉

        $username = "Lampa Dario";
        $service = new WelcomeService($this->fileUsersRepository);
        $this->assertEquals("Welcome, " . $username . "!", $service->welcomeUser($username));
    }

    /**
     * Test that the existing user is welcomed correctly.
     *
     * @return void
     */
    public function it_welcomes_existing_user(): void
    {
        // Uso di proposito un FileUsersRepository e non un mock

        $users = $this->fileUsersRepository->getById($this->usersId);
        $username = new Username("Lampa Dario");
        $subDate = new SubscriptionDate(new DateTime());
        $users->add($username, $subDate);
        $this->fileUsersRepository->save($users);
        $service = new WelcomeService($this->fileUsersRepository);
        $this->assertEquals("Welcome back, " . $username . "!", $service->welcomeUser($username));
    }


    /**
     * Deletes the file if it exists.
     *
     * @param string $filename The name of the file to delete.
     * @return void
     */
    public function deleteFile(string $filename): void
    {
        if (file_exists($filename)) {
            unlink($filename);
            echo "File " . $filename . " deleted.\n";
        } else {
            echo "File " . $filename . " doesn't exists.\n";
        }
    }

}
