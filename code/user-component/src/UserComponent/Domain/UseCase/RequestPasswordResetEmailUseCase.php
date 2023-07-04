<?php

declare(strict_types=1);

namespace App\UserComponent\Domain\UseCase;

use App\UserComponent\Domain\Exception\UserNotFoundException;
use App\UserComponent\Domain\Exception\ErrorSendingResetPasswordEmailException;
use App\UserComponent\Domain\Mailer\UserMailerInterface;
use App\UserComponent\Domain\Repository\UserRepositoryInterface;
use Exception;

class RequestPasswordResetEmailUseCase
{

    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserMailerInterface $mailer
    ){}

    /**
     * @throws ErrorSendingResetPasswordEmailException #When there's an error in the mailer
     * @throws UserNotFoundException #When the user is not found
     */
    public function execute(
        string $email
    ): bool
    {
        $user = $this->userRepository->findByEmail($email);
        if($user === null){
            throw new UserNotFoundException();
        }
        $user->setResetPasswordToken(bin2hex(random_bytes(16)));
        $user->setResetPasswordTokenExpiresAt(new \DateTime('+1 day'));
        $this->userRepository->save($user);
        try{
            $this->mailer->sendResetPasswordEmail($user);
        }
        catch(Exception $e){
            throw new ErrorSendingResetPasswordEmailException($e->getMessage());
        }
        return true;
    }

}