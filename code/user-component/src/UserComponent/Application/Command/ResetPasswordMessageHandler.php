<?php

declare(strict_types=1);

namespace App\UserComponent\Application\Command;

use App\UserComponent\Domain\Exception\ExpiredResetPasswordTokenException;
use App\UserComponent\Domain\Exception\InvalidResetPasswordTokenException;
use App\UserComponent\Domain\Exception\UserNotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use App\UserComponent\Domain\UseCase\ResetPasswordUseCase;
use App\UserComponent\Domain\Repository\TransactionRepositoryInterface;
use Psr\Log\LoggerInterface;
use Exception;

#[AsMessageHandler]
class ResetPasswordMessageHandler
{
    public function __construct(
        private readonly ResetPasswordUseCase $resetPasswordUseCase,
        private readonly TransactionRepositoryInterface $transactionRepository,
        private readonly LoggerInterface $logger
    ){}

    /**
     * @throws ExpiredResetPasswordTokenException
     * @throws UserNotFoundException
     * @throws InvalidResetPasswordTokenException
     */
    public function __invoke(ResetPasswordMessage $message): void
    {
        try{
            $this->transactionRepository->open();
            $passwordReset = $this->resetPasswordUseCase->execute(
                newPassword: $message->getUserSwagger()->password,
                resetPasswordToken: $message->getUserSwagger()->resetPasswordToken
            );
            $message->getUserSwagger()->passwordReset = $passwordReset;
            $this->transactionRepository->commit();
        }
        catch (Exception $e) {
            $this->transactionRepository->rollback();
            $this->logger->error($e->getMessage());
            throw $e;
        }
    }
}