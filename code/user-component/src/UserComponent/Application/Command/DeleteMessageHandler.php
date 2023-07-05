<?php

declare(strict_types=1);

namespace App\UserComponent\Application\Command;

use App\UserComponent\Domain\UseCase\DeleteUseCase;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use App\UserComponent\Domain\Repository\TransactionRepositoryInterface;
use Exception;

#[AsMessageHandler]
class DeleteMessageHandler
{

    public function __construct(
        private readonly DeleteUseCase $deleteUseCase,
        private readonly TransactionRepositoryInterface $transactionRepository
    ){}

    /**
     * @throws Exception
     */
    public function __invoke(DeleteMessage $message): void
    {
        try{
            $this->transactionRepository->open();
            $this->deleteUseCase->execute();
            $message->setResult(true);
            $this->transactionRepository->commit();
        }
        catch(Exception $e){
            $this->transactionRepository->rollback();
            throw $e;
        }
    }

}