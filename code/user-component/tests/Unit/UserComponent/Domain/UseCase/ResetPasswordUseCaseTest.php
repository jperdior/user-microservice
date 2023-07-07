<?php

declare(strict_types=1);

namespace App\Tests\Unit\UserComponent\Domain\UseCase;

use App\UserComponent\Domain\Entity\User;
use App\UserComponent\Domain\Exception\ExpiredResetPasswordTokenException;
use App\UserComponent\Domain\Exception\InvalidResetPasswordTokenException;
use App\UserComponent\Domain\Exception\UserNotFoundException;
use App\UserComponent\Domain\Repository\UserRepositoryInterface;
use App\UserComponent\Domain\UseCase\ResetPasswordUseCase;
use PHPUnit\Framework\TestCase;

class ResetPasswordUseCaseTest extends TestCase
{

    const NEW_PASSWORD = 'newPassword';
    const RESET_PASSWORD_TOKEN = 'resetPasswordToken';

    public function testExecute(): void
    {

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRequestingPasswordReset = $this->createMock(User::class);

        $useCase = new ResetPasswordUseCase(
            userRepository: $userRepository
        );

        $userRepository->expects($this->once())
            ->method('findByResetPasswordToken')
            ->with(
                resetPasswordToken: self::RESET_PASSWORD_TOKEN
            )
            ->willReturn($userRequestingPasswordReset);

        $userRequestingPasswordReset->expects($this->once())
            ->method('getResetPasswordTokenExpiresAt')
            ->willReturn(new \DateTime('+1 day'));

        $userRequestingPasswordReset->expects($this->once())
            ->method('getResetPasswordToken')
            ->willReturn(self::RESET_PASSWORD_TOKEN);

        $userRequestingPasswordReset->expects($this->once())
            ->method('setPassword')
            ->with(self::NEW_PASSWORD);

        $userRequestingPasswordReset->expects($this->once())
            ->method('setResetPasswordToken')
            ->with(null);

        $userRequestingPasswordReset->expects($this->once())
            ->method('setAccessToken')
            ->with(null);

        $userRequestingPasswordReset->expects($this->once())
            ->method('setRefreshToken')
            ->with(null);

        $userRepository->expects($this->once())
            ->method('save')
            ->with($userRequestingPasswordReset);

        $result = $useCase->execute(
            newPassword: self::NEW_PASSWORD,
            resetPasswordToken: self::RESET_PASSWORD_TOKEN
        );

        $this->assertTrue($result);

    }

    public function testUserNotFound():void
    {
        $userRepository = $this->createMock(UserRepositoryInterface::class);

        $useCase = new ResetPasswordUseCase(
            userRepository: $userRepository
        );

        $userRepository->expects($this->once())
            ->method('findByResetPasswordToken')
            ->with(
                resetPasswordToken: self::RESET_PASSWORD_TOKEN
            )
            ->willReturn(null);

        $this->expectException(UserNotFoundException::class);

        $useCase->execute(
            newPassword: self::NEW_PASSWORD,
            resetPasswordToken: self::RESET_PASSWORD_TOKEN
        );
    }

    public function testExpiredResetPasswordToken():void
    {
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRequestingPasswordReset = $this->createMock(User::class);

        $useCase = new ResetPasswordUseCase(
            userRepository: $userRepository
        );

        $userRepository->expects($this->once())
            ->method('findByResetPasswordToken')
            ->with(
                resetPasswordToken: self::RESET_PASSWORD_TOKEN
            )
            ->willReturn($userRequestingPasswordReset);

        $userRequestingPasswordReset->expects($this->once())
            ->method('getResetPasswordTokenExpiresAt')
            ->willReturn(new \DateTime('-1 day'));

        $this->expectException(ExpiredResetPasswordTokenException::class);

        $useCase->execute(
            newPassword: self::NEW_PASSWORD,
            resetPasswordToken: self::RESET_PASSWORD_TOKEN
        );
    }

    public function testInvalidResetPasswordToken():void
    {
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRequestingPasswordReset = $this->createMock(User::class);

        $useCase = new ResetPasswordUseCase(
            userRepository: $userRepository
        );

        $userRepository->expects($this->once())
            ->method('findByResetPasswordToken')
            ->with(
                resetPasswordToken: self::RESET_PASSWORD_TOKEN
            )
            ->willReturn($userRequestingPasswordReset);

        $userRequestingPasswordReset->expects($this->once())
            ->method('getResetPasswordTokenExpiresAt')
            ->willReturn(new \DateTime('+1 day'));

        $userRequestingPasswordReset->expects($this->once())
            ->method('getResetPasswordToken')
            ->willReturn('invalidToken');

        $this->expectException(InvalidResetPasswordTokenException::class);

        $useCase->execute(
            newPassword: self::NEW_PASSWORD,
            resetPasswordToken: self::RESET_PASSWORD_TOKEN
        );
    }

}