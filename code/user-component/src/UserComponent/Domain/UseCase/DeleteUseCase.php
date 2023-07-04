<?php

declare(strict_types=1);

namespace App\UserComponent\Domain\UseCase;

use App\UserComponent\Domain\Entity\User;

class DeleteUseCase
{

    public function __construct()
    {
    }

    public function execute(
        string $jwt
    ): bool
    {
        return true;
    }

}