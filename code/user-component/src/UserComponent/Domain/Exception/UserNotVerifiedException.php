<?php

declare(strict_types=1);

namespace App\UserComponent\Domain\Exception;

class UserNotVerifiedException extends \Exception
{
    public function __construct()
    {
        parent::__construct(
            message: 'User not verified',
            code: 401
        );
    }
}