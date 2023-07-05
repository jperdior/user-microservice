<?php

declare(strict_types=1);

namespace App\UserComponent\Domain\UseCase;

use App\UserComponent\Domain\Repository\UserRepositoryInterface;
use App\UserComponent\Domain\Security\SecurityInterface;

class DeleteUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly SecurityInterface $security,
    )
    {
    }

    public function execute(
    ): bool {
        $user = $this->userRepository->findById($this->security->getUser()->getId());
        $this->userRepository->delete($user);
        return true;
    }
}
