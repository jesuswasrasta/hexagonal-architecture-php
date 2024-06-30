<?php

namespace App\Infrastructure;

use Exception;
use Throwable;

/**
 * Class JsonUsersArchiveException
 *
 * This exception is thrown when there is an error with a JSON users archive.
 */
class JsonUsersArchiveException extends Exception
{
    private string $jsonFile = "";

    public function __construct($jsonFile, $message = "", $code = 0, Throwable $previous = null)
    {
        // Call the parent constructor
        parent::__construct($message, $code, $previous);

        // Store the invalid json file
        $this->jsonFile = $jsonFile;
    }

    // Add a custom method to get the invalid json file
    public function getInvalidJsonFile()
    {
        return $this->jsonFile;
    }
}
