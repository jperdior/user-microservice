<?php

declare(strict_types=1);

namespace App\UserComponent\Presentation\Controller;

use App\UserComponent\Application\Query\GetAuthenticatedUserMessage;
use App\UserComponent\Infrastructure\Messenger\SimpleQueryBus;
use App\UserComponent\Presentation\Swagger\UserSwagger;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use App\UserComponent\Presentation\Exception\ExceptionToResponseResolver;
use Exception;

#[AsController]
class GetAuthenticatedUserController
{
    public function __invoke(
        SimpleQueryBus $simpleQueryBus,
        UserSwagger $userSwagger,
        ExceptionToResponseResolver $exceptionToResponseResolver,
    ): JsonResponse|UserSwagger
    {
        try{
            return $simpleQueryBus->handle(query: new GetAuthenticatedUserMessage(userSwagger: $userSwagger));
        }
        catch(Exception $e){
            return $exceptionToResponseResolver->exceptionToJsonResponse(exception: $e);
        }

    }

}