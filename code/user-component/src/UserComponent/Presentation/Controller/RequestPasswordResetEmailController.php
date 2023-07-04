<?php

declare(strict_types=1);

namespace App\UserComponent\Presentation\Controller;

use ApiPlatform\Symfony\Validator\Exception\ValidationException;
use App\UserComponent\Presentation\Exception\ExceptionToResponseResolver;
use Exception;
use App\UserComponent\Presentation\Swagger\UserSwagger;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use App\UserComponent\Application\Command\RequestPasswordResetEmailMessage;
use App\UserComponent\Infrastructure\Messenger\SimpleCommandBus;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsController]
class RequestPasswordResetEmailController
{

    public function __invoke(
        ValidatorInterface $validator,
        SimpleCommandBus $commandBus,
        ExceptionToResponseResolver $exceptionToResponseResolver,
        UserSwagger $userSwagger,
    ): JsonResponse | UserSwagger {
        $errors = $validator->validate($userSwagger);

        if (count($errors) > 0) {
            throw new ValidationException(constraintViolationList: $errors);
        }
        try{
            $commandBus->dispatch(new RequestPasswordResetEmailMessage($userSwagger));
        }
        catch(Exception $e){
            return $exceptionToResponseResolver->exceptionToJsonResponse($e);
        }

        return $userSwagger;
    }

}