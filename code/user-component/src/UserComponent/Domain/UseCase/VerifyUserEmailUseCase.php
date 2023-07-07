<?php

declare(strict_types=1);

namespace App\UserComponent\Domain\UseCase;

use App\UserComponent\Domain\Exception\UserNotFoundException;
use App\UserComponent\Domain\Repository\UserRepositoryInterface;

class VerifyUserEmailUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    )
    {
    }

    /**
     * @throws UserNotFoundException
     */
    public function execute(
        string $verifyEmailToken
    ): bool {
        $user = $this->userRepository->findByVerifyEmailToken(
            verifyEmailToken: $verifyEmailToken
        );

        if ($user === null) {
            throw new UserNotFoundException();
        }

        $user->setVerifiedEmail(true);
        $user->setVerifyEmailToken(null);

        $this->userRepository->save(
            user: $user
        );

        return true;
    }
}
