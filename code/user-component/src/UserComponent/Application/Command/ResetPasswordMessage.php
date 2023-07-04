<?php

declare(strict_types=1);

namespace App\UserComponent\Application\Command;

use App\UserComponent\Infrastructure\Messenger\CommandMessage;
use App\UserComponent\Presentation\Swagger\UserSwagger;

class ResetPasswordMessage implements CommandMessage
{

    public function __construct(
        private UserSwagger $userSwagger
    ){}

    public function getUserSwagger(): UserSwagger
    {
        return $this->userSwagger;
    }


}