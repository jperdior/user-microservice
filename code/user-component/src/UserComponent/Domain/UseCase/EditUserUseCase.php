<?php

declare(strict_types=1);

namespace App\UserComponent\Domain\UseCase;

use App\UserComponent\Domain\Entity\User;
use App\UserComponent\Domain\Repository\UserRepositoryInterface;
use App\UserComponent\Domain\Security\SecurityInterface;

class EditUserUseCase
{

    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly SecurityInterface $security,
    )
    {
    }

    public function execute(
        ?string $name,
        ?string $lastName,
        ?string $email,
        ?string $password,
        ?bool $newsletter,
    ): ?User
    {
        $user = $this->userRepository->findById($this->security->getUser()->getId());

        if ($name !== null) {
            $user->setName($name);
        }

        if ($lastName !== null) {
            $user->setLastName($lastName);
        }

        if ($email !== null) {
            $user->setEmail($email);
        }

        if ($newsletter !== null) {
            $user->setNewsletter($newsletter);
        }

        if ($password !== null) {
            $user->setPassword($password);
        }

        $this->userRepository->save($user);
        return $user;
    }

}
