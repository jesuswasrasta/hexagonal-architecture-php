<?php
declare(strict_types=1);

namespace App\Infrastructure;

use App\Application\UsersRepositoryInterface;

class JsonUsersRepository implements UsersRepositoryInterface
{
    private string $filename;

    /**
     * @var string[]
     */
    private array $users = array();


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
     * @return array<string>|bool The users array if the file exists and contains valid JSON data,
     *                   otherwise false if there was an error reading the file.
     * @throws JsonUsersRepositoryException If the file contains invalid JSON data.
     */
    public function readUsers(): array|bool
    {
        // Create file if not exists
        if (!file_exists($this->filename)) {
            file_put_contents($this->filename, json_encode([]));
        }

        $fileContent = file_get_contents($this->filename);
        if ($fileContent) {
            $this->users = json_decode($fileContent, true);
        }

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new JsonUsersRepositoryException($this->filename, 'Invalid JSON data in file: ' . json_last_error_msg());
        }

        return $this->users;
    }

    /**
     * Writes users data to a JSON file.
     *
     * @param array<string> $users An array containing the users data.
     *
     * @throws JsonUsersRepositoryException If there is an error encoding the data to JSON format.
     */
    public function writeUsers(array $users): void
    {
        $jsonData = json_encode($users, JSON_PRETTY_PRINT);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new JsonUsersRepositoryException($this->filename, 'Error encoding data to JSON format: ' . json_last_error_msg());
        }

        file_put_contents($this->filename, $jsonData);
    }
}
