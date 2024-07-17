<?php
declare(strict_types=1);

namespace App\Domain\Users;

use App\Domain\Users\Results\ResultInterface;
use App\Domain\Users\Results\UserAdded;
use App\Domain\Users\Results\UserAlreadyPresent;
use App\Domain\Users\Results\UserNotFound;
use App\Domain\Users\Results\UserRemoved;
use App\Shared\Domain\Aggregate\AggregateInterface;
use App\Shared\Domain\Aggregate\AggregateRoot;

class Users extends AggregateRoot implements AggregateInterface
{
    /**
     * @var array<Username>
     */
    private array $users;

    private function __construct(UsersId $id)
    {
        $this->id = $id;
        $this->users = [];
    }

    public static function create(UsersId $id): self
    {
        return new self($id);
    }

    public function id(): UsersId
    {
        return $this->id;
    }

    /**
     * Adds a user to the collection if it doesn't already exist.
     *
     * @param Username $username The username of the user to add.
     * @return ResultInterface The result of the operation.
     */
    public function add(Username $username, SubscriptionDate $subDate): ResultInterface
    {
        if ($this->exists($username)) {
            return new UserAlreadyPresent($username);
        }
        $this->users[] = [$username, $subDate];
        return new UserAdded($username);
    }

    /**
     * Removes a user from the collection if it exists.
     *
     * @param Username $username The username of the user to remove.
     * @return ResultInterface The result of the operation.
     */
    public function remove(Username $username): ResultInterface
    {
        if (!$this->exists($username)) {
            return new UserNotFound($username);
        }

        foreach($this->users as $ind=>$user){
            if($user->equals($username)){
                unset($this->users[$ind]);
            }
        }

        return new UserRemoved($username);
    }

    /**
     * Check if a username exists in the users array.
     *
     * @param Username $username The username to check
     * @return bool Returns true if the username exists, false otherwise
     */
    private function exists(Username $username): bool
    {
        foreach ($this->users as $k => $v){
            if($v[0]->value() == $username->value()){
                return true;
            }
        }
        return false;
    }

    /**
     * Converts Username array to a string array.
     *
     * @return array<String> The array representation of the object.
     */
    public function toStringArray(): array
    {
        return array_map(function (array $values) {
            return $values[0]->value().' => '.$values[1]->value()->format('Y-m-d H:i:s');
        }, $this->users);
    }

    public function __equals(Users $users): bool
    {
        return $this->id === $users->id;
    }
}
