<?php

declare(strict_types=1);

namespace App\UserComponent\Presentation\Controller;

use App\UserComponent\Application\Command\DeleteMessage;
use App\UserComponent\Infrastructure\Messenger\SimpleCommandBus;
use App\UserComponent\Presentation\Exception\ExceptionToResponseResolver;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Exception;

#[AsController]
class DeleteController
{
    public function __invoke(
        SimpleCommandBus $commandBus,
        ExceptionToResponseResolver $exceptionToResponseResolver
    ): JsonResponse {
        try {
            $commandBus->dispatch(
                new DeleteMessage(
                    result: false
                )
            );
            return new JsonResponse(
                data: [
                    'result' => true
                ]
            );

        } catch (Exception $e) {
            return $exceptionToResponseResolver->exceptionToJsonResponse($e);
        }
    }
}