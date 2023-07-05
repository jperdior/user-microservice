<?php

declare(strict_types=1);

namespace App\Tests\Unit\UserComponent\Domain\UseCase;

use PHPUnit\Framework\TestCase;
use App\UserComponent\Domain\UseCase\DeleteUseCase;
use App\UserComponent\Domain\Repository\UserRepositoryInterface;
use App\UserComponent\Domain\Security\SecurityInterface;
use App\UserComponent\Domain\Entity\User;

class DeleteUseCaseTest extends TestCase
{
    public function testExecute(): void
    {
        $user = $this->createMock(User::class);
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $security = $this->createMock(SecurityInterface::class);
        $securityUser = $this->createMock(User::class);

        $security->expects($this->once())
            ->method('getUser')
            ->willReturn($securityUser);

        $securityUser->expects($this->once())
            ->method('getId')
            ->willReturn('1');

        $userRepository->expects($this->once())
            ->method('findById')
            ->with('1')
            ->willReturn($user);

        $userRepository->expects($this->once())
            ->method('delete')
            ->with($user);

        $useCase = new DeleteUseCase($userRepository, $security);
        $this->assertTrue($useCase->execute());
    }
}
