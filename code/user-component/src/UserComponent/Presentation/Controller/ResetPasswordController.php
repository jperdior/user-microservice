<?php

declare(strict_types=1);

namespace App\UserComponent\Presentation\Controller;

use ApiPlatform\Symfony\Validator\Exception\ValidationException;
use App\UserComponent\Application\Command\ResetPasswordMessage;
use App\UserComponent\Infrastructure\Messenger\SimpleCommandBus;
use App\UserComponent\Presentation\Swagger\UserSwagger;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsController]
class ResetPasswordController
{
    public function __invoke(
        ValidatorInterface $validator,
        SimpleCommandBus $commandBus,
        UserSwagger $userSwagger
    ): JsonResponse|UserSwagger {
        $errors = $validator->validate($userSwagger);

        if (count($errors) > 0) {
            throw new ValidationException(constraintViolationList: $errors);
        }
        try {
            $commandBus->dispatch(new ResetPasswordMessage($userSwagger));
        } catch (\Exception $e) {
            return new JsonResponse(
                data: [
                    'message' => $e->getMessage(),
                ],
                status: 400
            );
        }

        return $userSwagger;
    }
}
