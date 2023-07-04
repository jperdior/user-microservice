<?php

declare(strict_types=1);

namespace App\UserComponent\Domain\UseCase;

use App\UserComponent\Domain\Exception\IncorrectEmailOrPasswordException;
use App\UserComponent\Domain\Jwt\JwtServiceInterface;
use App\UserComponent\Domain\Repository\UserRepositoryInterface;
use App\UserComponent\Domain\Entity\User;

class LoginUseCase
{

    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly JwtServiceInterface $jwtService
    )
    {
    }

    /**
     * @throws IncorrectEmailOrPasswordException #When password is incorrect
     */
    public function execute(
        string $email,
        string $password
    ): User
    {
        $user = $this->userRepository->findByEmail($email);
        if (!$user) {
            throw new IncorrectEmailOrPasswordException();
        }

        /*if (!$user->isVerifiedEmail()) {
            throw new Exception('User not verified');
        }*/

        if (!$user->validatePassword($password)) {
            throw new IncorrectEmailOrPasswordException();
        }

        $accessToken = $this->jwtService->generateAccessToken(user: $user);
        if (
            $user->getRefreshToken() === null || $this->jwtService->isExpired($user->getRefreshToken())
        ) {
            $refreshToken = $this->jwtService->generateRefreshToken(user: $user);
            $user->setRefreshToken($refreshToken);
        }
        $user->setAccessToken($accessToken);
        $this->userRepository->save($user);

        return $user;

    }

}