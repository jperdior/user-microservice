<?php

declare(strict_types=1);

namespace App\UserComponent\Domain\Exception;

use Exception;

class ExpiredResetPasswordTokenException extends Exception
{

    public const MESSAGE = 'Reset password token expired';

    public function __construct()
    {
        parent::__construct(message: self::MESSAGE, code: 401);
    }

}