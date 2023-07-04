<?php

declare(strict_types=1);

namespace App\UserComponent\Domain\Exception;

use Exception;

class UserAlreadyExistsException extends Exception
{

    public const MESSAGE = 'User already exists';

    public function __construct()
    {
        parent::__construct(message: self::MESSAGE,code: 409);
    }

}