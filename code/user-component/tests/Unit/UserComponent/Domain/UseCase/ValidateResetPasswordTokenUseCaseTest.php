<?php

declare(strict_types=1);

namespace App\Tests\UserComponent\Domain\UseCase;

use App\UserComponent\Domain\Entity\User;
use App\UserComponent\Domain\Exception\ExpiredResetPasswordTokenException;
use App\UserComponent\Domain\Exception\InvalidResetPasswordTokenException;
use App\UserComponent\Domain\Exception\UserNotFoundException;
use App\UserComponent\Domain\Repository\UserRepositoryInterface;
use App\UserComponent\Domain\UseCase\ValidateResetPasswordTokenUseCase;
use PHPUnit\Framework\TestCase;

class ValidateResetPasswordTokenUseCaseTest extends TestCase
{

    public function testExecute(): void
    {
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userResettingPassword = $this->createMock(User::class);
        $useCase = new ValidateResetPasswordTokenUseCase(
            userRepository: $userRepository
        );
        $userRepository->expects($this->once())
            ->method('findByResetPasswordToken')
            ->with(
                resetPasswordToken: 'resetPasswordToken'
            )
            ->willReturn($userResettingPassword);
        $userResettingPassword->expects($this->once())
            ->method('getResetPasswordTokenExpiresAt')
            ->willReturn(new \DateTime('+1 day'));

        $userResettingPassword->expects($this->once())
            ->method('getResetPasswordToken')
            ->willReturn('resetPasswordToken');

        $result = $useCase->execute(
            resetPasswordToken: 'resetPasswordToken'
        );

        $this->assertTrue($result);

    }

    public function testUserNotFound():void
    {
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $useCase = new ValidateResetPasswordTokenUseCase(
            userRepository: $userRepository
        );
        $userRepository->expects($this->once())
            ->method('findByResetPasswordToken')
            ->with(
                resetPasswordToken: 'resetPasswordToken'
            )
            ->willReturn(null);

        $this->expectException(UserNotFoundException::class);

        $result = $useCase->execute(
            resetPasswordToken: 'resetPasswordToken'
        );

        $this->assertFalse($result);
    }

    public function testExpiredResetPasswordToken():void
    {
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userResettingPassword = $this->createMock(User::class);
        $useCase = new ValidateResetPasswordTokenUseCase(
            userRepository: $userRepository
        );
        $userRepository->expects($this->once())
            ->method('findByResetPasswordToken')
            ->with(
                resetPasswordToken: 'resetPasswordToken'
            )
            ->willReturn($userResettingPassword);
        $userResettingPassword->expects($this->once())
            ->method('getResetPasswordTokenExpiresAt')
            ->willReturn(new \DateTime('-1 day'));

        $this->expectException(ExpiredResetPasswordTokenException::class);

        $useCase->execute(
            resetPasswordToken: 'resetPasswordToken'
        );

    }

    public function testInvalidResetPasswordToken():void
    {
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userResettingPassword = $this->createMock(User::class);
        $useCase = new ValidateResetPasswordTokenUseCase(
            userRepository: $userRepository
        );
        $userRepository->expects($this->once())
            ->method('findByResetPasswordToken')
            ->with(
                resetPasswordToken: 'resetPasswordToken'
            )
            ->willReturn($userResettingPassword);
        $userResettingPassword->expects($this->once())
            ->method('getResetPasswordTokenExpiresAt')
            ->willReturn(new \DateTime('+1 day'));

        $userResettingPassword->expects($this->once())
            ->method('getResetPasswordToken')
            ->willReturn('invalidResetPasswordToken');

        $this->expectException(InvalidResetPasswordTokenException::class);

        $useCase->execute(
            resetPasswordToken: 'resetPasswordToken'
        );

    }

}
