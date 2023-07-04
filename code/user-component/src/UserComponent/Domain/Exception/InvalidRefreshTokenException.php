<?php

declare(strict_types=1);

namespace App\UserComponent\Domain\Exception;

use Exception;

class InvalidRefreshTokenException extends Exception
{

    public const MESSAGE = 'Invalid refresh token';

    public function __construct()
    {
        parent::__construct(message: self::MESSAGE, code: 401);
    }

}