<?php

declare(strict_types=1);

namespace App\UserComponent\Infrastructure\Mailer;

use App\UserComponent\Domain\Entity\User;
use App\UserComponent\Domain\Mailer\UserMailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SmtpUserMailer implements UserMailerInterface
{

    public function __construct(
        private readonly MailerInterface $mailer
    ){}

    /**
     * @throws TransportExceptionInterface
     */
    public function sendResetPasswordEmail(User $user): void
    {
        $domain = 'https://app.illiant.ai';
        if(getenv('APP_ENV') === 'dev' || getenv('APP_ENV') === 'test'){
            $domain = 'http://localhost:5173';
        }
        $email = (new Email())
            ->from('noreply@illiant.ai')
            ->to($user->getEmail())
            ->subject('Illiant.ai - Reset your password')
            ->text('Reset your password here: ' . $domain . '/reset-password/' . $user->getResetPasswordToken())
            ->html('<p>Reset your password here: ' . $domain . '/reset-password/' . $user->getResetPasswordToken() .'</p>');

        $this->mailer->send($email);
    }

    public function sendVerificationEmail(User $user): void
    {
        $domain = 'https://app.illiant.ai';
        if(getenv('APP_ENV') === 'dev' || getenv('APP_ENV') === 'test'){
            $domain = 'http://localhost:5173';
        }
        $email = (new Email())
            ->from('noreply@illiant.ai')
            ->to($user->getEmail())
            ->subject('Illiant.ai - Validate your email')
            ->text('Click here to validate your email: ' . $domain . '/verify-email/' . $user->getVerifyEmailToken())
            ->html('<p>Click here to validate your email: ' . $domain . '/verify-email/' . $user->getVerifyEmailToken() . '</p>');

        $this->mailer->send($email);
    }

}