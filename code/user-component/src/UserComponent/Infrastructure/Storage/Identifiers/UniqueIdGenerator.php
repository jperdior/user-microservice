<?php

declare(strict_types=1);

namespace App\UserComponent\Infrastructure\Storage\Identifiers;

use App\UserComponent\Domain\Entity\UniqueIdGeneratorInterface;
use Symfony\Component\Uid\Ulid;

class UniqueIdGenerator implements UniqueIdGeneratorInterface
{
    public function generateUlid(): string
    {
        return Ulid::generate();
    }
}
