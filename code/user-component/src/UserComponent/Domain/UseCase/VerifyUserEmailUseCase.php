<?php

declare(strict_types=1);

namespace App\UserComponent\Domain\UseCase;

class VerifyUserEmailUseCase
{
    public function __construct()
    {
    }

    public function execute(
        string $verifyEmailToken
    ): bool {
        return true;
    }
}
