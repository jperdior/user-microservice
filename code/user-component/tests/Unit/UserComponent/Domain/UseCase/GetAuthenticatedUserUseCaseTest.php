<?php

declare(strict_types=1);

namespace App\Tests\Unit\UserComponent\Domain\UseCase;

use App\UserComponent\Domain\Repository\UserRepositoryInterface;
use App\UserComponent\Domain\Security\SecurityInterface;
use App\UserComponent\Domain\UseCase\GetAuthenticatedUserUseCase;
use App\UserComponent\Domain\Entity\User;

use PHPUnit\Framework\TestCase;

class GetAuthenticatedUserUseCaseTest extends TestCase
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

        $useCase = new GetAuthenticatedUserUseCase($security, $userRepository);
        $authenticatedUser = $useCase->execute();
        $this->assertInstanceOf(User::class, $authenticatedUser);
        $this->assertSame($user, $authenticatedUser);
    }

}
