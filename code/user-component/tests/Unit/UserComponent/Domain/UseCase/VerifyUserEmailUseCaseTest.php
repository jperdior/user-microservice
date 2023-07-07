<?php

declare(strict_types=1);

namespace App\Tests\Unit\UserComponent\Domain\UseCase;

use App\UserComponent\Domain\Entity\User;
use App\UserComponent\Domain\Exception\UserNotFoundException;
use App\UserComponent\Domain\Repository\UserRepositoryInterface;
use App\UserComponent\Domain\UseCase\VerifyUserEmailUseCase;
use PHPUnit\Framework\TestCase;

class VerifyUserEmailUseCaseTest extends TestCase
{
    private VerifyUserEmailUseCase $verifyUserEmailUseCase;
    private UserRepositoryInterface $userRepository;
    private User $userVerified;

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->verifyUserEmailUseCase = new VerifyUserEmailUseCase(
            userRepository: $this->userRepository
        );
        $this->userVerified = $this->createMock(User::class);
    }

    public function testExecute(): void
    {
        $this->userRepository->expects($this->once())
            ->method('findByVerifyEmailToken')
            ->with('token')
            ->willReturn($this->userVerified);

        $this->userVerified->expects($this->once())
            ->method('setVerifiedEmail')
            ->with(true);

        $this->userVerified->expects($this->once())
            ->method('setVerifyEmailToken')
            ->with(null);

        $this->userRepository->expects($this->once())
            ->method('save');

        $result = $this->verifyUserEmailUseCase->execute(
            verifyEmailToken: 'token'
        );

        $this->assertTrue($result);
    }

    public function testUserNotExists(): void
    {
        $this->userRepository->expects($this->once())
            ->method('findByVerifyEmailToken')
            ->willReturn(null);

        $this->expectException(UserNotFoundException::class);

        $this->verifyUserEmailUseCase->execute(
            verifyEmailToken: 'token'
        );
    }
}