<?php

declare(strict_types=1);

namespace App\UserComponent\Domain\Jwt;

use App\UserComponent\Domain\Entity\User;

interface JwtServiceInterface
{
    public function generateAccessToken(User $user): string;

    public function generateRefreshToken(User $user): string;

    public function generateResetPasswordToken(User $user): string;

    public function decode(string $jwt): array;

    public function getSubject(string $jwt): string;

    public function isExpired(string $jwt): bool;

}