<?php

declare(strict_types=1);

namespace App\Tests\Unit\UserComponent\Domain\UseCase;

use App\UserComponent\Domain\Entity\User;
use App\UserComponent\Domain\Exception\UserNotFoundException;
use App\UserComponent\Domain\Repository\UserRepositoryInterface;
use App\UserComponent\Domain\Mailer\UserMailerInterface;
use App\UserComponent\Domain\UseCase\RequestPasswordResetEmailUseCase;
use PHPUnit\Framework\TestCase;

class RequestPasswordResetEmailUseCaseTest extends TestCase
{
    public const EMAIL = 'test@example.com';

    public function testExecute(): void
    {
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userMailer = $this->createMock(UserMailerInterface::class);
        $userRequestingPasswordReset = $this->createMock(User::class);

        $useCase = new RequestPasswordResetEmailUseCase(
            userRepository: $userRepository,
            mailer: $userMailer
        );

        $userRepository->expects($this->once())
            ->method('findByEmail')
            ->with(
                email: self::EMAIL
            )
            ->willReturn($userRequestingPasswordReset);

        $userRequestingPasswordReset->expects($this->once())
            ->method('setResetPasswordToken')
            ->with(
                token: $this->isType('string')
            );

        $userRequestingPasswordReset->expects($this->once())
            ->method('setResetPasswordTokenExpiresAt')
            ->with(
                expiresAt: $this->isInstanceOf(\DateTime::class)
            );

        $userRepository->expects($this->once())
            ->method('save')
            ->with(
                user: $userRequestingPasswordReset
            );

        $userMailer->expects($this->once())
            ->method('sendResetPasswordEmail')
            ->with(
                user: $userRequestingPasswordReset
            );

        $result = $useCase->execute(
            email: self::EMAIL
        );

        $this->assertTrue($result);

    }

    public function testUserNotFoundExceptionProvider(): void
    {

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userMailer = $this->createMock(UserMailerInterface::class);

        $useCase = new RequestPasswordResetEmailUseCase(
            userRepository: $userRepository,
            mailer: $userMailer
        );

        $userRepository->expects($this->once())
            ->method('findByEmail')
            ->with(
                email: self::EMAIL
            )
            ->willReturn(null);

        $this->expectException(UserNotFoundException::class);

        $useCase->execute(
            email: self::EMAIL
        );


    }

}
