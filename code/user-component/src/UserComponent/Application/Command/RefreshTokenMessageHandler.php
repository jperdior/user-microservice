<?php

declare(strict_types=1);

namespace App\UserComponent\Application\Command;

use App\UserComponent\Application\DataTransformer\UserDataTransformer;
use App\UserComponent\Domain\Exception\ExpiredRefreshTokenException;
use App\UserComponent\Domain\Exception\InvalidRefreshTokenException;
use App\UserComponent\Domain\Exception\RevokedRefreshTokenException;
use App\UserComponent\Domain\Exception\UserNotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use App\UserComponent\Domain\UseCase\RefreshTokenUseCase;
use App\UserComponent\Domain\Repository\TransactionRepositoryInterface;
use Exception;

#[AsMessageHandler]
class RefreshTokenMessageHandler
{

    public function __construct(
        private readonly RefreshTokenUseCase $refreshTokenUseCase,
        private readonly TransactionRepositoryInterface $transactionRepository,
        private readonly UserDataTransformer $userDataTransformer
    ) {
    }

    /**
     * @throws ExpiredRefreshTokenException
     * @throws RevokedRefreshTokenException
     * @throws InvalidRefreshTokenException
     * @throws UserNotFoundException
     */
    public function __invoke(RefreshTokenMessage $message): void
    {
        try {
            $this->transactionRepository->open();
            $user = $this->refreshTokenUseCase->execute($message->getUserSwagger()->refreshToken);
            $this->userDataTransformer->writeAuthTokens(user: $user, userSwagger: $message->getUserSwagger());
            $this->transactionRepository->commit();
        } catch (Exception $e) {
            $this->transactionRepository->rollback();
            throw $e;
        }
    }

}