<?php

declare(strict_types=1);

namespace App\UserComponent\Application\Command;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use App\UserComponent\Domain\UseCase\EditUserUseCase;
use Exception;
use App\UserComponent\Application\DataTransformer\UserDataTransformer;
use App\UserComponent\Domain\Repository\TransactionRepositoryInterface;

#[AsMessageHandler]
class EditUserMessageHandler{

    public function __construct(
        private readonly EditUserUseCase $editUserUseCase,
        private readonly UserDataTransformer $userDataTransformer,
        private readonly TransactionRepositoryInterface $transactionRepository
    )
    {}

    public function __invoke(EditUserMessage $message): void
    {
        try{
            $this->transactionRepository->open();
            $user = $this->editUserUseCase->execute(
                name: $message->getUserSwagger()->name,
                lastName: $message->getUserSwagger()->lastName,
                email: $message->getUserSwagger()->email,
                password: $message->getUserSwagger()->password,
                newsletter: $message->getUserSwagger()->newsletter
            );
            $this->userDataTransformer->writeUserSwagger(
                user: $user,
                userSwagger: $message->getUserSwagger()
            );
            $this->transactionRepository->commit();
        }
        catch (Exception $e){
            $this->transactionRepository->rollback();
            throw $e;
        }
    }

}