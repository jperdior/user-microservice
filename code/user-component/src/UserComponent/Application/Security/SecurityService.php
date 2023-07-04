<?php

declare(strict_types=1);

namespace App\UserComponent\Application\Security;

use App\UserComponent\Domain\Entity\User;
use App\UserComponent\Domain\Security\SecurityInterface;
use Symfony\Bundle\SecurityBundle\Security;


class SecurityService implements SecurityInterface
{
    public function __construct(
        private readonly Security $security
    )
    {
    }

    public function getUser(): User
    {
        return $this->security->getUser();
    }
}
