<?php

declare(strict_types=1);

namespace App\Tests\Unit\UserComponent\Domain\UseCase;

use App\UserComponent\Domain\Exception\ExpiredRefreshTokenException;
use App\UserComponent\Domain\Exception\InvalidRefreshTokenException;
use App\UserComponent\Domain\Exception\UserNotFoundException;
use PHPUnit\Framework\TestCase;
use App\UserComponent\Domain\UseCase\RefreshTokenUseCase;
use App\UserComponent\Domain\Repository\UserRepositoryInterface;
use App\UserComponent\Domain\Jwt\JwtServiceInterface;
use App\UserComponent\Domain\Entity\User;
use Exception;

class RefreshTokenUseCaseTest extends TestCase
{

    const REFRESH_TOKEN = 'refreshToken';
    const NEW_ACCESS_TOKEN = 'newAccessToken';

    public function testExecute(): void
    {
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $jwtService = $this->createMock(JwtServiceInterface::class);

        $loggedInUser = $this->createMock(User::class);


        $useCase = new RefreshTokenUseCase(
            userRepository: $userRepository,
            jwtService: $jwtService
        );

        $jwtService->expects($this->once())
            ->method('getSubject')
            ->willReturn('1');

        $userRepository->expects($this->once())
            ->method('findById')
            ->with('1')
            ->willReturn($loggedInUser);

        $loggedInUser->expects($this->once())
            ->method('getRefreshToken')
            ->willReturn('refreshToken');

        $jwtService->expects($this->once())
            ->method('isExpired')
            ->with(self::REFRESH_TOKEN)
            ->willReturn(false);

        $jwtService->expects($this->once())
            ->method('generateAccessToken')
            ->with($loggedInUser)
            ->willReturn(self::NEW_ACCESS_TOKEN);

        $loggedInUser->expects($this->once())
            ->method('setAccessToken')
            ->with(self::NEW_ACCESS_TOKEN);

        $userRepository->expects($this->once())
            ->method('save')
            ->with($loggedInUser);

        $refreshedTokenUser = $useCase->execute(self::REFRESH_TOKEN);
        $this->assertInstanceOf(User::class, $refreshedTokenUser);

    }

    public function testInvalidRefreshToken(): void
    {
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $jwtService = $this->createMock(JwtServiceInterface::class);

        $useCase = new RefreshTokenUseCase(
            userRepository: $userRepository,
            jwtService: $jwtService
        );

        $jwtService->expects($this->once())
            ->method('getSubject')
            ->willThrowException(new Exception());

        $this->expectException(InvalidRefreshTokenException::class);

        $useCase->execute(self::REFRESH_TOKEN);

    }

    public function testUserNotFound(): void
    {
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $jwtService = $this->createMock(JwtServiceInterface::class);

        $useCase = new RefreshTokenUseCase(
            userRepository: $userRepository,
            jwtService: $jwtService
        );

        $jwtService->expects($this->once())
            ->method('getSubject')
            ->willReturn('1');

        $userRepository->expects($this->once())
            ->method('findById')
            ->with('1')
            ->willReturn(null);

        $this->expectException(UserNotFoundException::class);

        $useCase->execute(self::REFRESH_TOKEN);

    }

    public function testExpiredRefreshToken(): void
    {
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $jwtService = $this->createMock(JwtServiceInterface::class);

        $loggedInUser = $this->createMock(User::class);

        $useCase = new RefreshTokenUseCase(
            userRepository: $userRepository,
            jwtService: $jwtService
        );

        $jwtService->expects($this->once())
            ->method('getSubject')
            ->willReturn('1');

        $userRepository->expects($this->once())
            ->method('findById')
            ->with('1')
            ->willReturn($loggedInUser);

        $loggedInUser->expects($this->once())
            ->method('getRefreshToken')
            ->willReturn('refreshToken');

        $jwtService->expects($this->once())
            ->method('isExpired')
            ->with(self::REFRESH_TOKEN)
            ->willReturn(true);

        $this->expectException(ExpiredRefreshTokenException::class);

        $useCase->execute(self::REFRESH_TOKEN);

    }

}