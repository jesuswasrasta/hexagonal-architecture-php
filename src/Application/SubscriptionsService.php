<?php
declare(strict_types=1);

namespace App\Application;

use App\Domain\Users\Results\UserAdded;
use App\Domain\Users\Results\UserAlreadyPresent;
use App\Domain\Users\SubscriptionDate;
use App\Domain\Users\Username;
use App\Domain\Users\Users;
use App\Domain\Users\UsersId;
use App\Shared\Domain\Repository\RepositoryInterface;
use Cassandra\Date;

class SubscriptionsService
{
    private RepositoryInterface $usersRepository;

    public function __construct(RepositoryInterface $usersRepository)
    {
        $this->usersRepository = $usersRepository;
    }

    public function listSubscribedUsers(string $date): string
    {
        $domainId = new UsersId("d9208ab6-6402-49e9-a61d-111111111111");

        /** @var Users $users */
        $users = $this->usersRepository->getById($domainId);

        //da data stringa a data utilizzabile per Aggregato Users
        $data = new \DateTime($date . "-01");
        $subcriptionDate = new SubscriptionDate($data);
        $result = $users->listUserBySubscriptionDate($subcriptionDate);
        //query all'aggregato

        //return result
        return $result->getMessage();
    }
}
