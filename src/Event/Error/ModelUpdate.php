<?php

declare(strict_types=1);

namespace App\Event\Error;

use Error;

class ModelUpdate extends Error
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
