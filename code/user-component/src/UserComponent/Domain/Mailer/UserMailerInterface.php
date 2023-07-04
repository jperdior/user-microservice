<?php

declare(strict_types=1);

namespace App\UserComponent\Domain\Mailer;

use App\UserComponent\Domain\Entity\User;

interface UserMailerInterface
{
    public function sendResetPasswordEmail(User $user): void;

    public function sendVerificationEmail(User $user): void;
}

