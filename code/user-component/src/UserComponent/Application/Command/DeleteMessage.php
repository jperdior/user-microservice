<?php

declare(strict_types=1);

namespace App\UserComponent\Application\Command;

use App\UserComponent\Infrastructure\Messenger\CommandMessage;
class DeleteMessage implements CommandMessage
{
    public function __construct(
        private bool $result
    ){}

    public function getResult(): bool
    {
        return $this->result;
    }

    public function setResult(bool $result): void
    {
        $this->result = $result;
    }

}