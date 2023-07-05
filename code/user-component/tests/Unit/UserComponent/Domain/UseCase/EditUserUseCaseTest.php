<?php

declare(strict_types=1);

namespace App\Tests\Unit\UserComponent\Domain\UseCase;

use PHPUnit\Framework\TestCase;
use App\UserComponent\Domain\UseCase\EditUserUseCase;
use App\UserComponent\Domain\Repository\UserRepositoryInterface;
use App\UserComponent\Domain\Security\SecurityInterface;
use App\UserComponent\Domain\Entity\User;

class EditUserUseCaseTest extends TestCase
{

    public const EMAIL = 'test@example.com';
    public const NAME = 'John';
    public const LAST_NAME = 'Doe';
    public const NEWSLETTER = true;

    public const PASSWORD = '12345';
    public const TERMS_ACCEPTED = true;

    public function testExecute(): void
    {
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $security = $this->createMock(SecurityInterface::class);
        $securityUser = $this->createMock(User::class);

        $user = new User();
        $user->setEmail(self::EMAIL);
        $user->setName(self::NAME);
        $user->setLastName(self::LAST_NAME);
        $user->setNewsletter(self::NEWSLETTER);
        $user->setTermsAccepted(self::TERMS_ACCEPTED);

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
            ->method('save')
            ->with($user);

        $useCase = new EditUserUseCase($userRepository, $security);
        $editedUser = $useCase->execute(
            self::NAME,
            self::LAST_NAME,
            self::EMAIL,
            self::PASSWORD,
            self::NEWSLETTER
        );
        $this->assertInstanceOf(User::class, $editedUser);
        $this->assertEquals($editedUser->getName(), self::NAME);
        $this->assertEquals($editedUser->getLastName(), self::LAST_NAME);
        $this->assertEquals($editedUser->getEmail(), self::EMAIL);
        $this->assertEquals($editedUser->getPassword(), $editedUser->validatePassword(self::PASSWORD));
        $this->assertEquals($editedUser->isNewsletter(), self::NEWSLETTER);

    }

}