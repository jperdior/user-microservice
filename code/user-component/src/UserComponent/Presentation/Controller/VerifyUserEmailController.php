<?php

declare(strict_types=1);

namespace App\UserComponent\Presentation\Controller;

use App\UserComponent\Application\Command\VerifyUserEmailMessage;
use App\UserComponent\Presentation\Exception\ExceptionToResponseResolver;
use App\UserComponent\Infrastructure\Messenger\SimpleCommandBus;
use App\UserComponent\Presentation\Swagger\UserSwagger;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class VerifyUserEmailController
{
    public function __invoke(
        UserSwagger $userSwagger,
        SimpleCommandBus $commandBus,
        ExceptionToResponseResolver $exceptionToResponseResolver
    ): JsonResponse
    {
        try{
            $commandBus->dispatch(command: new VerifyUserEmailMessage(userSwagger: $userSwagger));
            return new JsonResponse(
                data: [
                    'success' => true
                ]
            );
        }
        catch (\Exception $exception){
            return $exceptionToResponseResolver->exceptionToJsonResponse(
                exception: $exception
            );
        }
    }
}