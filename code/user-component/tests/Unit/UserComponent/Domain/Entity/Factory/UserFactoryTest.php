<?php

declare(strict_types=1);

namespace App\Tests\Unit\UserComponent\Domain\Entity\Factory;

use App\UserComponent\Domain\Entity\Factory\UserFactory;
use App\UserComponent\Domain\Entity\User;
use App\UserComponent\Domain\Entity\UniqueIdGeneratorInterface;
use PHPUnit\Framework\TestCase;

class UserFactoryTest extends TestCase
{

    public const EMAIL = 'test@example.com';
    public const NAME = 'John';
    public const LAST_NAME = 'Doe';
    public const PASSWORD = '123456';
    public const NEWSLETTER = true;
    public const TERMS_ACCEPTED = true;

    public function testCreate(): void
    {
        $uniqueIdGenerator = $this->createMock(UniqueIdGeneratorInterface::class);

        $factory = new UserFactory(
            uniqueIdGenerator: $uniqueIdGenerator
        );

        $uniqueIdGenerator->expects($this->once())
            ->method('generateUlid')
            ->willReturn('01F9ZJZJZJZJZJZJZJZJZJZJZJ');

        $user = $factory->create(
            name: self::NAME,
            lastName: self::LAST_NAME,
            email: self::EMAIL,
            password: self::PASSWORD,
            newsletter: self::NEWSLETTER,
            termsAccepted: self::TERMS_ACCEPTED
        );

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals(self::NAME, $user->getName());
        $this->assertEquals(self::LAST_NAME, $user->getLastName());
        $this->assertEquals(self::EMAIL, $user->getEmail());
        $this->assertEquals(self::NEWSLETTER, $user->isNewsletter());
        $this->assertEquals(self::TERMS_ACCEPTED, $user->isTermsAccepted());
        $this->assertEquals(true, $user->validatePassword(self::PASSWORD));

        $this->assertEquals('01F9ZJZJZJZJZJZJZJZJZJZJZJ', $user->getId());

    }
}
