<?php

declare(strict_types=1);

namespace App\Tests\Unit\UserComponent\Domain\UseCase;

use App\UserComponent\Domain\Entity\Factory\UserFactory;
use App\UserComponent\Domain\Entity\User;
use App\UserComponent\Domain\Exception\UserAlreadyExistsException;
use App\UserComponent\Domain\Jwt\JwtServiceInterface;
use App\UserComponent\Domain\Repository\UserRepositoryInterface;
use App\UserComponent\Domain\UseCase\RegisterUseCase;
use PHPUnit\Framework\TestCase;

class RegisterUseCaseTest extends TestCase
{
    public const EMAIL = 'test@example.com';
    public const NAME = 'John';
    public const LAST_NAME = 'Doe';
    public const PASSWORD = '123456';
    public const NEWSLETTER = true;
    public const TERMS_ACCEPTED = true;

    public function testExecute(): void
    {
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userFactory = $this->createMock(UserFactory::class);
        $jwtService = $this->createMock(JwtServiceInterface::class);

        $useCase = new RegisterUseCase(
            userFactory: $userFactory,
            userRepository: $userRepository,
            jwtService: $jwtService
        );

        $userRepository->expects($this->once())
            ->method('findByEmail')
            ->with(
                email: self::EMAIL
            );

        $registeredUser = new User();
        $registeredUser->setEmail(self::EMAIL);
        $registeredUser->setName(self::NAME);
        $registeredUser->setLastName(self::LAST_NAME);
        $registeredUser->setPassword(self::PASSWORD);
        $registeredUser->setNewsletter(self::NEWSLETTER);
        $registeredUser->setTermsAccepted(self::TERMS_ACCEPTED);

        $userFactory->expects($this->once())
            ->method('create')
            ->with(
                name: self::NAME,
                lastName: self::LAST_NAME,
                email: self::EMAIL,
                password: self::PASSWORD,
                newsletter: self::NEWSLETTER,
                termsAccepted: self::TERMS_ACCEPTED
            )
            ->willReturn($registeredUser);

        $jwtService->expects($this->once())
            ->method('generateAccessToken')
            ->with(
                user: $registeredUser
            );

        $jwtService->expects($this->once())
            ->method('generateRefreshToken')
            ->with(
                user: $registeredUser
            );

        $userRepository->expects($this->once())
            ->method('save')
            ->with(
                user: $registeredUser
            );

        $user = $useCase->execute(
            name: self::NAME,
            lastName: self::LAST_NAME,
            email: self::EMAIL,
            password: self::PASSWORD,
            newsletter: self::NEWSLETTER,
            termsAccepted: self::TERMS_ACCEPTED
        );

        $this->assertEquals($registeredUser, $user);
    }

    public function testUserAlreadyExists(): void
    {
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userFactory = $this->createMock(UserFactory::class);
        $jwtService = $this->createMock(JwtServiceInterface::class);

        $useCase = new RegisterUseCase(
            userFactory: $userFactory,
            userRepository: $userRepository,
            jwtService: $jwtService
        );

        $userRepository->expects($this->once())
            ->method('findByEmail')
            ->with(
                email: self::EMAIL
            )
            ->willReturn(new User());

        $this->expectException(UserAlreadyExistsException::class);

        $useCase->execute(
            name: self::NAME,
            lastName: self::LAST_NAME,
            email: self::EMAIL,
            password: self::PASSWORD,
            newsletter: self::NEWSLETTER,
            termsAccepted: self::TERMS_ACCEPTED
        );

    }
}
