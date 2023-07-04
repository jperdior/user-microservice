<?php

declare(strict_types=1);

namespace App\UserComponent\Presentation\Exception;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

class ExceptionToResponseResolver
{

    public function exceptionToJsonResponse(Exception $exception): JsonResponse
    {
        if($exception instanceof HandlerFailedException){
            $exception = $exception->getPrevious();
        }
        if($exception->getCode() === 0){
            $exception = new Exception(
                message: 'Internal server error',
                code: 500
            );
        }
        return new JsonResponse(
            data: [
                'message' => $exception->getMessage()
            ],
            status: $exception->getCode()
        );

    }

}