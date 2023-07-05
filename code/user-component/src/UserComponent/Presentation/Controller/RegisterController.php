<?php

declare(strict_types=1);

namespace App\UserComponent\Presentation\Controller;

use ApiPlatform\Symfony\Validator\Exception\ValidationException;
use App\UserComponent\Application\Command\RegisterMessage;
use App\UserComponent\Infrastructure\Messenger\SimpleCommandBus;
use App\UserComponent\Presentation\Exception\ExceptionToResponseResolver;
use App\UserComponent\Presentation\Swagger\UserSwagger;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsController]
class RegisterController
{
    public function __invoke(
        ValidatorInterface $validator,
        SimpleCommandBus $commandBus,
        ExceptionToResponseResolver $exceptionToResponseResolver,
        UserSwagger $userSwagger
    ): UserSwagger|JsonResponse {
        $errors = $validator->validate($userSwagger);

        if (count($errors) > 0) {
            throw new ValidationException(constraintViolationList: $errors);
        }
        try {
            $commandBus->dispatch(new RegisterMessage($userSwagger));
        } catch (\Exception $e) {
            return $exceptionToResponseResolver->exceptionToJsonResponse($e);
        }

        return $userSwagger;
    }
}
