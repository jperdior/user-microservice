<?php

declare(strict_types=1);

namespace App\UserComponent\Application\Query;

use App\UserComponent\Application\DataTransformer\UserDataTransformer;
use App\UserComponent\Presentation\Swagger\UserSwagger;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use App\UserComponent\Domain\UseCase\GetAuthenticatedUserUseCase;

#[AsMessageHandler]
class GetAuthenticatedUserMessageHandler
{

    public function __construct(
        private readonly GetAuthenticatedUserUseCase    $getUserByAccessTokenUseCase,
        private readonly UserDataTransformer            $userDataTransformer,
    )
    {
    }


    public function __invoke(GetAuthenticatedUserMessage $getUserByAccessTokenMessage): UserSwagger
    {
            $user = $this->getUserByAccessTokenUseCase->execute();
            $this->userDataTransformer->writeUserSwagger(user: $user, userSwagger: $getUserByAccessTokenMessage->getUserSwagger());
            return $getUserByAccessTokenMessage->getUserSwagger();
    }

}