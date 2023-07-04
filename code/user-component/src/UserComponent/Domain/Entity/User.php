<?php

declare(strict_types=1);

namespace App\UserComponent\Domain\Entity;

use DateTime;
use Exception;

class User
{

    private string $id;

    private string $name;

    private string $lastName;

    private string $email;

    private string $password;

    private string $salt;

    private string $verifyEmailToken;

    private bool $verifiedEmail;

    private bool $termsAccepted;

    private bool $newsletter;

    private ?string $accessToken;

    private ?string $refreshToken;

    private ?string $resetPasswordToken;

    private ?DateTime $resetPasswordTokenExpiresAt;

    private DateTime $createdAt;

    private DateTime $updatedAt;

    private array $roles = [];

    private string $stripeCustomerId;

    private bool $trialUsed;


    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
        $this->salt = bin2hex(random_bytes(16));
        $this->verifyEmailToken = bin2hex(random_bytes(16));
        $this->verifiedEmail = false;
        $this->newsletter = false;
        $this->trialUsed = true;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $this->encodePassword(password: $password);
    }

    private function encodePassword(string $password): string
    {
        return password_hash($password . $this->salt, PASSWORD_DEFAULT);
    }

    public function validatePassword(string $password): bool
    {
        return password_verify($password . $this->salt, $this->password);
    }

    public function getSalt(): string
    {
        return $this->salt;
    }

    public function setSalt(string $salt): void
    {
        $this->salt = $salt;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getVerifyEmailToken(): string
    {
        return $this->verifyEmailToken;
    }

    public function setVerifyEmailToken(string $verifyEmailToken): void
    {
        $this->verifyEmailToken = $verifyEmailToken;
    }

    public function isVerifiedEmail(): bool
    {
        return $this->verifiedEmail;
    }

    public function setVerifiedEmail(bool $verifiedEmail): void
    {
        $this->verifiedEmail = $verifiedEmail;
    }

    public function isNewsletter(): bool
    {
        return $this->newsletter;
    }

    public function setNewsletter(bool $newsletter): void
    {
        $this->newsletter = $newsletter;
    }

    public function isTermsAccepted(): bool
    {
        return $this->termsAccepted;
    }

    public function setTermsAccepted(bool $termsAccepted): void
    {
        $this->termsAccepted = $termsAccepted;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function setAccessToken(?string $accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(?string $refreshToken): void
    {
        $this->refreshToken = $refreshToken;
    }

    public function getResetPasswordToken(): ?string
    {
        return $this->resetPasswordToken;
    }

    public function setResetPasswordToken(?string $resetPasswordToken): void
    {
        $this->resetPasswordToken = $resetPasswordToken;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setStripeCustomerId(string $stripeCustomerId): void
    {
        $this->stripeCustomerId = $stripeCustomerId;
    }

    public function getStripeCustomerId(): string
    {
        return $this->stripeCustomerId;
    }

    public function isTrialUsed(): bool
    {
        return $this->trialUsed;
    }

    public function setTrialUsed(bool $trialUsed): void
    {
        $this->trialUsed = $trialUsed;
    }

    public function getResetPasswordTokenExpiresAt(): ?DateTime
    {
        return $this->resetPasswordTokenExpiresAt;
    }

    public function setResetPasswordTokenExpiresAt(?DateTime $resetPasswordTokenExpiresAt): void
    {
        $this->resetPasswordTokenExpiresAt = $resetPasswordTokenExpiresAt;
    }

}