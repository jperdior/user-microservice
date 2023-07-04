<?php

declare(strict_types=1);

namespace App\UserComponent\Infrastructure\Storage\Doctrine\Repository;

use App\UserComponent\Domain\Entity\User;
use App\UserComponent\Domain\Repository\UserRepositoryInterface;

class UserOrmRepository extends AbstractOrmRepository implements UserRepositoryInterface
{
    protected function getClass(): string
    {
        return User::class;
    }

    public function save(User $user): void
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function findByEmail(string $email): ?User
    {
        return $this->findOneBy(['email' => $email]);
    }

    public function findById(string $id): ?User
    {
        return $this->find($id);
    }

    public function findByResetPasswordToken(string $resetPasswordToken): ?User
    {
        return $this->findOneBy(['resetPasswordToken' => $resetPasswordToken]);
    }
}