<?php

declare(strict_types=1);

namespace App\UserComponent\Domain\Exception;

use Exception;

class ExpiredRefreshTokenException extends Exception
{

    public const MESSAGE = 'Refresh token expired';

    public function __construct()
    {
        parent::__construct(message: self::MESSAGE, code: 401);
    }

}