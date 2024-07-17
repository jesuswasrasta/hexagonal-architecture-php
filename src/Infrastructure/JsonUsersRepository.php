<?php
declare(strict_types=1);

namespace App\Infrastructure;

use App\Domain\Users\SubscriptionDate;
use App\Domain\Users\Username;
use App\Domain\Users\Users;
use App\Shared\Domain\Aggregate\AggregateInterface;
use App\Shared\Domain\Aggregate\DomainIdInterface;
use App\Shared\Domain\Repository\RepositoryInterface;

class JsonUsersRepository implements RepositoryInterface
{
    private string $filename;
    private string $delimiter = '=>'; // delimiter string

    /**
     * Retrieves an aggregate by its ID from a JSON file.
     *
     * @param DomainIdInterface $id The ID of the aggregate to retrieve.
     *
     * @return AggregateInterface The retrieved aggregate.
     *
     * @throws JsonUsersRepositoryException If the JSON file contains invalid data or the file cannot be read.
     */
    public function getById(DomainIdInterface $id): AggregateInterface
    {
        /** @var Users $users */
        $users = Users::create($id);

        $this->filename = $id->value() . ".json";
        if (!file_exists($this->filename)) {
            return $users;
        }

        $fileContent = file_get_contents($this->filename);
        if ($fileContent !== false) {
            $usersJson = json_decode($fileContent, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($usersJson)) {
                foreach ($usersJson as $userJson)
                {
                    $user = explode($this->delimiter, $userJson);
                    $users->add(new Username(trim($user[0])), new SubscriptionDate(new \DateTime($user[1])));
                }
            } else {
                throw new JsonUsersRepositoryException($this->filename, 'Invalid JSON data in file: ' . json_last_error_msg());
            }
        } else {
            throw new JsonUsersRepositoryException($this->filename, 'Failed to read file contents');
        }

        return $users;
    }

    /**
     * Saves the aggregate to a JSON file.
     *
     * @param AggregateInterface $aggregate The aggregate to be saved.
     *
     * @throws JsonUsersRepositoryException If there is an error encoding the data to JSON format.
     */
    public function save(AggregateInterface $aggregate): void
    {
        $this->filename = $aggregate->id()->value() . ".json";
        $jsonData = json_encode($aggregate->toStringArray(), JSON_PRETTY_PRINT);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new JsonUsersRepositoryException($this->filename, 'Error encoding data to JSON format: ' . json_last_error_msg());
        }

        file_put_contents($this->filename, $jsonData);
    }
}
