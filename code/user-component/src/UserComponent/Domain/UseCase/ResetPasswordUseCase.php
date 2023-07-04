<?php

declare(strict_types=1);

namespace App\UserComponent\Domain\UseCase;

use App\UserComponent\Domain\Exception\ExpiredResetPasswordTokenException;
use App\UserComponent\Domain\Exception\InvalidResetPasswordTokenException;
use App\UserComponent\Domain\Exception\UserNotFoundException;
use App\UserComponent\Domain\Repository\UserRepositoryInterface;

class ResetPasswordUseCase
{

    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ){}

    /**
     * @throws ExpiredResetPasswordTokenException
     * @throws UserNotFoundException
     * @throws InvalidResetPasswordTokenException
     */
    public function execute(
        string $newPassword,
        string $resetPasswordToken
    ): bool {
        $user = $this->userRepository->findByResetPasswordToken($resetPasswordToken);
        if (!$user) {
            throw new UserNotFoundException();
        }
        if($user->getResetPasswordTokenExpiresAt() < new \DateTime()){
            throw new ExpiredResetPasswordTokenException();
        }
        if($resetPasswordToken !== $user->getResetPasswordToken()){
            throw new InvalidResetPasswordTokenException();
        }
        $user->setPassword($newPassword);
        $user->setResetPasswordToken(null);
        $user->setAccessToken(null);
        $user->setRefreshToken(null);
        $this->userRepository->save($user);
        return true;

    }

}