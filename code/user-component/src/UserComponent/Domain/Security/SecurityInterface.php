<?php

declare(strict_types=1);

namespace App\UserComponent\Domain\Security;

use App\UserComponent\Domain\Entity\User;

interface SecurityInterface
{
    public function getUser(): User;
}