<?php

namespace App\Infrastructure;

use App\Application\UsersArchiveInterface;

class JsonUsersArchive implements UsersArchiveInterface
{
    private string $filename;
    private array $users = array();


    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @throws JsonUsersArchiveException
     */
    public function readUsers(): array|bool
    {
        // Check if file exists before trying to read
        if (file_exists($this->filename)) {
            $this->users = file($this->filename, FILE_IGNORE_NEW_LINES);
        } else {
            // Create file
            file_put_contents($this->filename, json_encode([]));
        }

        $fileContent = file_get_contents($this->filename);
        $users = json_decode($fileContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new JsonUsersArchiveException($this->filename, 'Invalid JSON data in file: ' . json_last_error_msg());
        }

        return $users;
    }

    /**
     * @throws JsonUsersArchiveException
     */
    public function writeUsers($users): void
    {
        $jsonData = json_encode($users, JSON_PRETTY_PRINT);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new JsonUsersArchiveException($this->filename, 'Error encoding data to JSON format: ' . json_last_error_msg());
        }

        file_put_contents($this->filename, $jsonData);
    }
}
