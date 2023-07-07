<?php

declare(strict_types=1);

namespace App\UserComponent\Application\Command;

use App\UserComponent\Presentation\Swagger\UserSwagger;
use App\UserComponent\Infrastructure\Messenger\CommandMessage;

class VerifyUserEmailMessage implements CommandMessage
{
    public function __construct(
        private UserSwagger $userSwagger,
    ) {
    }

    public function getUserSwagger(): UserSwagger
    {
        return $this->userSwagger;
    }
}