<?php

declare(strict_types=1);

namespace App\UserComponent\Application\Command;

use App\UserComponent\Infrastructure\Messenger\CommandMessage;
use App\UserComponent\Presentation\Swagger\UserSwagger;

class RequestPasswordResetEmailMessage implements CommandMessage
{
    public function __construct(
        public UserSwagger $userSwagger
    ) {}

    public function getUserSwagger(): UserSwagger
    {
        return $this->userSwagger;
    }
}