<?php

namespace App\Event\Exception\Doctrine;

use Exception;

// @TODO: improve info at mechanism to display relevent not-critical info with unused $exception
class SQLException extends Exception
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function fromCreation(Exception $exception): self
    {
        return new self('Error while inserting info into database');
    }

    public static function fromUpdate(Exception $exception)
    {
        return new self('Error while updating info in database');
    }
}
