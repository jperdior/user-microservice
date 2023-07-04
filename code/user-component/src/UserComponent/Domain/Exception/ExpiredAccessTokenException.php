<?php

declare(strict_types=1);

namespace App\UserComponent\Domain\Exception;

use Exception;

class ExpiredAccessTokenException extends Exception
{

    public const MESSAGE = 'Access token expired';
    public function __construct()
    {
        parent::__construct(message: self::MESSAGE,code: 401);
    }

}