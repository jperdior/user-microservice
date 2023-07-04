<?php

declare(strict_types=1);

namespace App\UserComponent\Domain\UseCase;

use App\UserComponent\Domain\Entity\Factory\UserFactory;
use App\UserComponent\Domain\Exception\UserAlreadyExistsException;
use App\UserComponent\Domain\Repository\UserRepositoryInterface;
use App\UserComponent\Domain\Entity\User;
use App\UserComponent\Domain\Jwt\JwtServiceInterface;

class RegisterUseCase
{

    public function __construct(
        private readonly UserFactory $userFactory,
        private readonly UserRepositoryInterface $userRepository,
        private readonly JwtServiceInterface $jwtService
    )
    {
    }

    /**
     * @throws UserAlreadyExistsException #When user already exists
     */
    public function execute(
        string $name,
        string $lastName,
        string $email,
        string $password,
        bool $newsletter,
        bool $termsAccepted,
    ): User
    {
        $existingUser = $this->userRepository->findByEmail($email);
        if ($existingUser) {
            throw new UserAlreadyExistsException();
        }

        $user = $this->userFactory->create(
            name: $name,
            lastName: $lastName,
            email: $email,
            password: $password,
            newsletter: $newsletter,
            termsAccepted: $termsAccepted
        );
        //Until email verification we give the access token back directly on register
        $accessToken = $this->jwtService->generateAccessToken(user: $user);
        $refreshToken = $this->jwtService->generateRefreshToken(user: $user);
        $user->setAccessToken($accessToken);
        $user->setRefreshToken($refreshToken);

        $this->userRepository->save($user);

        return $user;
    }


}