<?php

declare(strict_types=1);

namespace App\UserComponent\Domain\Exception;

use Exception;

class UserNotFoundException extends Exception
{

    public const MESSAGE = 'User not found';

    public function __construct()
    {
        parent::__construct(message: self::MESSAGE, code: 404);
    }

}