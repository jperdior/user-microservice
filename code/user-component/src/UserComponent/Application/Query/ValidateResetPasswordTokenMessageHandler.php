<?php

declare(strict_types=1);

namespace App\UserComponent\Application\Query;

use App\UserComponent\Presentation\Swagger\UserSwagger;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use App\UserComponent\Domain\UseCase\ValidateResetPasswordTokenUseCase;

#[AsMessageHandler]
class ValidateResetPasswordTokenMessageHandler
{

    public function __construct(
        private readonly ValidateResetPasswordTokenUseCase $validateResetPasswordTokenUseCase
    )
    {
    }

    public function __invoke(ValidateResetPasswordTokenMessage $message): UserSwagger
    {
        $validResetPasswordToken = $this->validateResetPasswordTokenUseCase->execute($message->getUserSwagger()->resetPasswordToken);
        $message->getUserSwagger()->validResetPasswordToken = $validResetPasswordToken;
        return $message->getUserSwagger();
    }

}