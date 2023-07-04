<?php

declare(strict_types=1);

namespace App\UserComponent\Application\Command;

use App\UserComponent\Application\DataTransformer\UserDataTransformer;
use App\UserComponent\Domain\Exception\UserAlreadyExistsException;
use App\UserComponent\Domain\Repository\TransactionRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use App\UserComponent\Domain\UseCase\RegisterUseCase;
use Exception;

#[AsMessageHandler]
class RegisterMessageHandler
{

    public function __construct(
        private readonly TransactionRepositoryInterface $transactionRepository,
        private readonly RegisterUseCase $registerUseCase,
        private readonly UserDataTransformer $userDataTransformer
    )
    {
    }


    /**
     * @throws UserAlreadyExistsException
     */
    public function __invoke(RegisterMessage $message): void
    {
        try {
            $this->transactionRepository->open();
            $user = $this->registerUseCase->execute(
                name: $message->getUserSwagger()->name,
                lastName: $message->getUserSwagger()->lastName,
                email: $message->getUserSwagger()->email,
                password: $message->getUserSwagger()->password,
                newsletter: $message->getUserSwagger()->newsletter,
                termsAccepted: $message->getUserSwagger()->termsAccepted
            );
            $this->userDataTransformer->writeAuthTokens(user: $user, userSwagger: $message->getUserSwagger());
            $this->transactionRepository->commit();
        }
        catch (Exception $e) {
            $this->transactionRepository->rollback();
            throw $e;
        }
    }

}