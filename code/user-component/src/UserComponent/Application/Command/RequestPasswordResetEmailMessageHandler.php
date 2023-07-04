<?php

declare(strict_types=1);

namespace App\UserComponent\Application\Command;

use App\UserComponent\Domain\Exception\ErrorSendingResetPasswordEmailException;
use App\UserComponent\Domain\Exception\UserNotFoundException;
use App\UserComponent\Domain\UseCase\RequestPasswordResetEmailUseCase;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use App\UserComponent\Domain\Repository\TransactionRepositoryInterface;
use Exception;

#[AsMessageHandler]
class RequestPasswordResetEmailMessageHandler
{

    public function __construct(
        private readonly RequestPasswordResetEmailUseCase $requestPasswordResetEmailUseCase,
        private readonly TransactionRepositoryInterface $transactionRepository
    ) {}

    /**
     * @throws ErrorSendingResetPasswordEmailException
     * @throws UserNotFoundException
     */
    public function __invoke(RequestPasswordResetEmailMessage $message): void
    {
        try {
            $this->transactionRepository->open();
            $emailSent = $this->requestPasswordResetEmailUseCase->execute($message->getUserSwagger()->email);
            $message->getUserSwagger()->resetPasswordEmailSent = $emailSent;
            $this->transactionRepository->commit();
        }
        catch (Exception $e) {
            $this->transactionRepository->rollback();
            throw $e;
        }
    }

}