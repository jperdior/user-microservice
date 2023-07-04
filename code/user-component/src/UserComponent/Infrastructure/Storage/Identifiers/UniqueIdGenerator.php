<?php

declare(strict_types=1);

namespace App\UserComponent\Infrastructure\Storage\Identifiers;

use Symfony\Component\Uid\Ulid;
use App\UserComponent\Domain\Entity\UniqueIdGeneratorInterface;

class UniqueIdGenerator implements UniqueIdGeneratorInterface
{
    public function generateUlid(): string
    {
        return Ulid::generate();
    }
}