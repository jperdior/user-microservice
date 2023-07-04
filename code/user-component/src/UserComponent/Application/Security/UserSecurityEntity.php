<?php

declare(strict_types=1);

namespace App\UserComponent\Application\Security;

use App\UserComponent\Domain\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

class UserSecurityEntity extends User implements UserInterface
{

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return $this->id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }



}