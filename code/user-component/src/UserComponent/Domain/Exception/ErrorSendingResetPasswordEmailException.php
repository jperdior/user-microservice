<?php

declare(strict_types=1);

namespace App\UserComponent\Domain\Exception;

use Exception;

class ErrorSendingResetPasswordEmailException extends Exception
{

    const MESSAGE = 'Error sending reset password email: ';
    public function __construct($message)
    {
        parent::__construct(message: self::MESSAGE . $message);
    }
}