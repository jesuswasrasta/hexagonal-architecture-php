<?php
declare(strict_types=1);

namespace App\Infrastructure;

use App\Application\UsersRepositoryInterface;
use App\Domain\Users;
use App\Domain\UsersId;

class JsonUsersRepository implements UsersRepositoryInterface
{
    private string $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    /**
     * Reads the users from the file.
     *
     * If the file does not exist, it will create an empty file.
     * Reads the contents of the file and parses it into an array.
     * If the file content is not valid JSON, it throws a JsonUsersRepositoryException.
     *
     * @return Users|bool The users array if the file exists and contains valid JSON data,
     *                   otherwise false if there was an error reading the file.
     * @throws JsonUsersRepositoryException If the file contains invalid JSON data.
     */
    public function readUsers(): Users|bool
    {
        // Create file if not exists
        if (!file_exists($this->filename)) {
            file_put_contents($this->filename, "[]");
        }

        $users = Users::create(UsersId::random());

        $fileContent = file_get_contents($this->filename);
        if ($fileContent !== false) {
            $usersJson = json_decode($fileContent, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($usersJson)) {
                $users->addFromStringArray($usersJson);
            } else {
                throw new JsonUsersRepositoryException($this->filename, 'Invalid JSON data in file: ' . json_last_error_msg());
            }
        } else {
            throw new JsonUsersRepositoryException($this->filename, 'Failed to read file contents');
        }

        return $users;
    }

    /**
     * Writes users data to a JSON file.
     *
     * @param Users $users An array containing the users' data.
     *
     * @throws JsonUsersRepositoryException If there is an error encoding the data to JSON format.
     */
    public function writeUsers(Users $users): void
    {
        $jsonData = json_encode($users->toStringArray(), JSON_PRETTY_PRINT);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new JsonUsersRepositoryException($this->filename, 'Error encoding data to JSON format: ' . json_last_error_msg());
        }

        file_put_contents($this->filename, $jsonData);
    }
}
