<?php

declare(strict_types=1);

namespace App\UserComponent\Presentation\Controller;

use App\UserComponent\Application\Command\EditUserMessage;
use App\UserComponent\Presentation\Swagger\UserSwagger;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\UserComponent\Presentation\Exception\ExceptionToResponseResolver;
use Symfony\Component\HttpKernel\Attribute\AsController;
use App\UserComponent\Infrastructure\Messenger\SimpleCommandBus;
use Exception;

#[AsController]
class EditUserController
{

    public function __invoke(
        UserSwagger $userSwagger,
        SimpleCommandBus $commandBus,
        ExceptionToResponseResolver $exceptionToResponseResolver
    ): UserSwagger | JsonResponse
    {
        try{
            $commandBus->dispatch(
                new EditUserMessage(
                    userSwagger: $userSwagger
                )
            );
            return $userSwagger;
        }
        catch (Exception $e){
            return $exceptionToResponseResolver->exceptionToJsonResponse($e);
        }
    }


}