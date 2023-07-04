<?php

declare(strict_types=1);

namespace App\UserComponent\Presentation\Controller;

use App\UserComponent\Application\Query\GetAuthenticatedUserMessage;
use App\UserComponent\Application\Query\ValidateResetPasswordTokenMessage;
use App\UserComponent\Infrastructure\Messenger\SimpleQueryBus;
use App\UserComponent\Presentation\Swagger\UserSwagger;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Exception;

#[AsController]
class ValidateResetPasswordTokenController
{


    public function __invoke(
        SimpleQueryBus $simpleQueryBus,
        UserSwagger $userSwagger
    ): JsonResponse|UserSwagger
    {
        try{
            return $simpleQueryBus->handle(query: new ValidateResetPasswordTokenMessage(userSwagger: $userSwagger));
        }
        catch(Exception $e){
            return new JsonResponse($e->getMessage(), 400);
        }
    }

}