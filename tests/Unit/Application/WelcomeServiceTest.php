<?php
declare(strict_types=1);

namespace App\Unit\Application;
use App\Application\WelcomeService;
use App\Domain\Users\SubscriptionDate;
use App\Domain\Users\Username;
use App\Domain\Users\Users;
use App\Domain\Users\UsersId;
use App\Infrastructure\FileUsersRepository;
use DateTime;
use PHPUnit\Framework\Attributes\Test;
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
        $this->deleteFile("d9208ab6-6402-49e9-a61d-111111111111.txt");
        parent::tearDown();
    }

    #[Test]
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

    #[Test]
    public function it_welcomes_existing_user(): void
    {
        // Uso di proposito un FileUsersRepository e non un mock)
        // WelcomeService usa internamente un UsersId hardcoded (d9208...),
        // quindi dobbiamo pre-popolare quel file.

        $hardcodedId = new UsersId("d9208ab6-6402-49e9-a61d-111111111111");
        $users = Users::create($hardcodedId);
        $usernameString = "Lampa Dario";
        $users->add(new Username($usernameString), new SubscriptionDate(new DateTime()));
        $this->fileUsersRepository->save($users);

        $service = new WelcomeService($this->fileUsersRepository);
        $this->assertEquals("Welcome back, " . $usernameString . "!", $service->welcomeUser($usernameString));
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
