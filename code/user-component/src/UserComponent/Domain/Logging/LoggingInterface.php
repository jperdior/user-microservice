<?php

declare(strict_types=1);

namespace App\UserComponent\Domain\Logging;

interface LoggingInterface
{
    public function error(string $message): void;

    public function info(string $message): void;
}