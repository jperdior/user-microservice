<?php

declare(strict_types=1);

namespace App\UserComponent\Domain\Exception;

use Exception;

class InvalidResetPasswordTokenException extends Exception
{

    public const MESSAGE = 'Invalid reset password token';

    public function __construct()
    {
        parent::__construct(message: self::MESSAGE, code: 401);
    }

}