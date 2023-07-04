<?php

declare(strict_types=1);

namespace App\UserComponent\Domain\UseCase;

use App\UserComponent\Domain\Entity\User;
use App\UserComponent\Domain\Repository\UserRepositoryInterface;
use App\UserComponent\Domain\Security\SecurityInterface;

class GetAuthenticatedUserUseCase
{

    public function __construct(
        private readonly SecurityInterface $security,
        private readonly UserRepositoryInterface $userRepository
    )
    {
    }

    public function execute(
    ): User
    {
        return $this->userRepository->findById($this->security->getUser()->getId());

    }

}