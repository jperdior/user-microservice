<?php

declare(strict_types=1);

namespace App\UserComponent\Application\Query;

use App\UserComponent\Infrastructure\Messenger\QueryMessage;
use App\UserComponent\Presentation\Swagger\UserSwagger;

class ValidateResetPasswordTokenMessage implements QueryMessage
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