<?php

declare(strict_types=1);

namespace App\UserComponent\Domain\Exception;

use Exception;

class IncorrectEmailOrPasswordException extends Exception
{

    public const MESSAGE = 'Incorrect email or password';

    public function __construct()
    {
        parent::__construct(message: self::MESSAGE, code: 401);
    }

}