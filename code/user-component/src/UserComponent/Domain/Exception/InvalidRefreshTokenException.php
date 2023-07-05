<?php

declare(strict_types=1);

namespace App\UserComponent\Domain\Exception;

class InvalidRefreshTokenException extends \Exception
{
    public const MESSAGE = 'Invalid refresh token: %s';

    public function __construct(?string $message)
    {
        if(!$message){
            $message = 'Invalid refresh token';
        }
        parent::__construct(sprintf(self::MESSAGE, $message), 401);
    }
}
