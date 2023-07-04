<?php

declare(strict_types=1);

namespace App\UserComponent\Domain\Exception;

use Exception;

class InvalidAccessTokenException extends Exception
{

    public const MESSAGE = 'Invalid access token';

    public function __construct()
    {
        parent::__construct(message: self::MESSAGE);
    }

}