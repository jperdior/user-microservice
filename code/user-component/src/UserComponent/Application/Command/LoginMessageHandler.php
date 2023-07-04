<?php

declare(strict_types=1);

namespace App\UserComponent\Application\Command;

use App\UserComponent\Domain\Exception\IncorrectEmailOrPasswordException;
use App\UserComponent\Domain\Exception\UserNotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use App\UserComponent\Domain\UseCase\LoginUseCase;
use App\UserComponent\Domain\Repository\TransactionRepositoryInterface;
use App\UserComponent\Application\DataTransformer\UserDataTransformer;
use Exception;

#[AsMessageHandler]
class LoginMessageHandler
{

    public function __construct(
        private readonly LoginUseCase $loginUseCase,
        private readonly TransactionRepositoryInterface $transactionRepository,
        private readonly UserDataTransformer $userDataTransformer
    )
    {
    }


    /**
     * @throws IncorrectEmailOrPasswordException
     * @throws UserNotFoundException
     */
    public function __invoke(LoginMessage $message): void
    {
        $this->transactionRepository->open();
        try {
            $user = $this->loginUseCase->execute(
                $message->getUserSwagger()->email,
                $message->getUserSwagger()->password
            );
            $this->userDataTransformer->writeAuthTokens(
                user: $user,
                userSwagger: $message->getUserSwagger()
            );
            $this->transactionRepository->commit();
        } catch (Exception $e) {
            $this->transactionRepository->rollBack();
            throw $e;
        }
    }

}