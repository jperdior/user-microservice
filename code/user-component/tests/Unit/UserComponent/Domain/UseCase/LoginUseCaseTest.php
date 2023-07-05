<?php

declare(strict_types=1);

namespace App\Tests\Unit\UserComponent\Domain\UseCase;

use PHPUnit\Framework\TestCase;
use App\UserComponent\Domain\Entity\User;
use App\UserComponent\Domain\Repository\UserRepositoryInterface;
use App\UserComponent\Domain\UseCase\LoginUseCase;
use App\UserComponent\Domain\Jwt\JwtServiceInterface;
use App\UserComponent\Domain\Exception\IncorrectEmailOrPasswordException;

class LoginUseCaseTest extends TestCase
{
    public const EMAIL = 'test@example.com';
    public const PASSWORD = '123456';

    public function testSuccessfulLoginWithNotExpiredRefreshToken(): void
    {
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $jwtService = $this->createMock(JwtServiceInterface::class);
        $loggedUser = $this->createMock(User::class);

        $useCase = new LoginUseCase(
            userRepository: $userRepository,
            jwtService: $jwtService
        );

        $userRepository->expects($this->once())
            ->method('findByEmail')
            ->with(
                email: self::EMAIL
            )
            ->willReturn($loggedUser);

        $loggedUser->expects($this->once())
            ->method('validatePassword')
            ->with(
                password: self::PASSWORD
            )
            ->willReturn(true);


        $jwtService->expects($this->once())
            ->method('generateAccessToken')
            ->with(
                user: $loggedUser
            );

        $loggedUser->expects($this->exactly(2))
            ->method('getRefreshToken')
            ->willReturn('refreshToken');

        $jwtService->expects($this->once())
            ->method('isExpired')
            ->willReturn(false);


        $loggedUser->expects($this->once())
            ->method('setAccessToken');

        $userRepository->expects($this->once())
            ->method('save')
            ->with(
                user: $loggedUser
            );

        $user = $useCase->execute(
            email: self::EMAIL,
            password: self::PASSWORD
        );

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($loggedUser, $user);
    }

    public function testSuccessfulLoginWithExpiredRefreshToken(): void
    {
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $jwtService = $this->createMock(JwtServiceInterface::class);
        $loggedUser = $this->createMock(User::class);

        $useCase = new LoginUseCase(
            userRepository: $userRepository,
            jwtService: $jwtService
        );

        $userRepository->expects($this->once())
            ->method('findByEmail')
            ->with(
                email: self::EMAIL
            )
            ->willReturn($loggedUser);

        $loggedUser->expects($this->once())
            ->method('validatePassword')
            ->with(
                password: self::PASSWORD
            )
            ->willReturn(true);

        $jwtService->expects($this->once())
            ->method('generateAccessToken')
            ->with(
                user: $loggedUser
            );

        $loggedUser->expects($this->exactly(2))
            ->method('getRefreshToken')
            ->willReturn('refreshToken');

        $jwtService->expects($this->once())
            ->method('isExpired')
            ->willReturn(true);

        $jwtService->expects($this->once())
            ->method('generateRefreshToken')
            ->willReturn('newRefreshToken');

        $loggedUser->expects($this->once())
            ->method('setRefreshToken');

        $loggedUser->expects($this->once())
            ->method('setAccessToken');

        $userRepository->expects($this->once())
            ->method('save')
            ->with(
                user: $loggedUser
            );

        $user = $useCase->execute(
            email: self::EMAIL,
            password: self::PASSWORD
        );

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($loggedUser, $user);
    }

    public function testIncorrectEmail(): void
    {
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $jwtService = $this->createMock(JwtServiceInterface::class);

        $useCase = new LoginUseCase(
            userRepository: $userRepository,
            jwtService: $jwtService
        );

        $userRepository->expects($this->once())
            ->method('findByEmail')
            ->with(
                email: self::EMAIL
            )
            ->willReturn(null);

        $this->expectException(IncorrectEmailOrPasswordException::class);

        $useCase->execute(
            email: self::EMAIL,
            password: self::PASSWORD
        );
    }

    public function testIncorrectPassword(): void
    {

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $jwtService = $this->createMock(JwtServiceInterface::class);
        $loggedUser = $this->createMock(User::class);

        $useCase = new LoginUseCase(
            userRepository: $userRepository,
            jwtService: $jwtService
        );

        $userRepository->expects($this->once())
            ->method('findByEmail')
            ->with(
                email: self::EMAIL
            )
            ->willReturn($loggedUser);

        $loggedUser->expects($this->once())
            ->method('validatePassword')
            ->with(
                password: self::PASSWORD
            )
            ->willReturn(false);

        $this->expectException(IncorrectEmailOrPasswordException::class);

        $useCase->execute(
            email: self::EMAIL,
            password: self::PASSWORD
        );

    }

}