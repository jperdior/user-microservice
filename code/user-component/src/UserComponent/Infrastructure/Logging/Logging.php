<?php

declare(strict_types=1);

namespace App\UserComponent\Infrastructure\Logging;

use App\UserComponent\Domain\Logging\LoggingInterface;

class Logging implements LoggingInterface
{
    public function error(string $message): void
    {
        error_log($message);
    }

    public function info(string $message): void
    {
        error_log($message);
    }
}