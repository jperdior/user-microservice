<?php

declare(strict_types=1);

namespace App\UserComponent\Domain\Entity\Factory;

use App\UserComponent\Domain\Entity\User;
use App\UserComponent\Domain\Entity\UniqueIdGeneratorInterface;

class UserFactory
{

    public function __construct(
        private readonly UniqueIdGeneratorInterface $uniqueIdGenerator
    ) {
    }

    public function create(
        string $name,
        string $lastName,
        string $email,
        string $password,
        bool $newsletter,
        bool $termsAccepted,
    ): User
    {
        $user = new User();
        $user->setId($this->uniqueIdGenerator->generateUlid());
        $user->setName($name);
        $user->setLastName($lastName);
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setNewsletter($newsletter);
        $user->setTermsAccepted($termsAccepted);
        return $user;
    }

}