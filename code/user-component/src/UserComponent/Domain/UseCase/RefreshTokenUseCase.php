<?php

declare(strict_types=1);

namespace App\UserComponent\Domain\UseCase;

use App\UserComponent\Domain\Entity\User;
use App\UserComponent\Domain\Exception\ExpiredRefreshTokenException;
use App\UserComponent\Domain\Exception\InvalidRefreshTokenException;
use App\UserComponent\Domain\Exception\UserNotFoundException;
use App\UserComponent\Domain\Jwt\JwtServiceInterface;
use App\UserComponent\Domain\Repository\UserRepositoryInterface;
use Exception;

class RefreshTokenUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly JwtServiceInterface $jwtService
    ) {
    }

    /**
     * @throws UserNotFoundException        #When user is not found
     * @throws InvalidRefreshTokenException #When refresh token is invalid
     * @throws ExpiredRefreshTokenException #When refresh token is expired
     */
    public function execute(
        string $refreshToken
    ): User {
        try {
            $userId = $this->jwtService->getSubject($refreshToken);
        } catch (Exception $e) {
            throw new InvalidRefreshTokenException($e->getMessage());
        }

        $user = $this->userRepository->findById($userId);
        if (!$user) {
            throw new UserNotFoundException();
        }
        if ($user->getRefreshToken() !== $refreshToken) {
            throw new InvalidRefreshTokenException();
        }
        if ($this->jwtService->isExpired($refreshToken)) {
            throw new ExpiredRefreshTokenException();
        }
        $accessToken = $this->jwtService->generateAccessToken(user: $user);
        $user->setAccessToken($accessToken);

        $this->userRepository->save($user);

        return $user;
    }
}
