<?php
declare(strict_types=1);

namespace App\Application;

use App\Domain\Users\Results\UserNotFound;
use App\Domain\Users\Results\UserRemoved;
use App\Domain\Users\Username;
use App\Domain\Users\UsersId;
use App\Shared\Domain\Repository\RepositoryInterface;

class GoodByeService
{
    private RepositoryInterface $usersRepository;

    public function __construct(RepositoryInterface $usersRepository)
    {
        $this->usersRepository = $usersRepository;
    }

    public function goodByeUser(string $user): string
    {
        $domainId = new UsersId("d9208ab6-6402-49e9-a61d-111111111111");
        $users = $this->usersRepository->getById($domainId);

        $username = new Username($user);
        $result = $users->remove($username);

        if ($result instanceof UserRemoved) {
            $this->usersRepository->save($users);
            return "Goodbye, " . $user . "!";
        }
        elseif ($result instanceof UserNotFound) {
            return "User " . $user . " not found!";
        }
        else{
            return $result->getMessage();
        }
    }
}
