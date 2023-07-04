<?php

declare(strict_types=1);

namespace App\UserComponent\Domain\Repository;

use App\UserComponent\Domain\Entity\User;

interface UserRepositoryInterface
{
    public function save(User $user): void;

    public function findByEmail(string $email): ?User;

    public function findByResetPasswordToken(string $resetPasswordToken): ?User;
    public function findById(string $id): ?User;
}