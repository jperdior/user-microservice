<?php

declare(strict_types=1);

namespace App\UserComponent\Application\Command;

use App\UserComponent\Domain\Exception\UserNotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use App\UserComponent\Domain\Repository\TransactionRepositoryInterface;
use App\UserComponent\Domain\UseCase\VerifyUserEmailUseCase;

#[AsMessageHandler]
class VerifyUserEmailMessageHandler
{
    public function __construct(
        private readonly VerifyUserEmailUseCase $verifyUserEmailUseCase,
        private readonly TransactionRepositoryInterface $transactionRepository
    ) {
    }

    /**
     * @throws UserNotFoundException
     */
    public function __invoke(
        VerifyUserEmailMessage $verifyUserEmailMessage
    ): void {
        try{
            $this->transactionRepository->open();
            $this->verifyUserEmailUseCase->execute(
                verifyEmailToken: $verifyUserEmailMessage->getUserSwagger()->verifyEmailToken
            );
            $this->transactionRepository->commit();
        }
        catch (\Exception $exception){
            $this->transactionRepository->rollback();
            throw $exception;
        }
    }
}