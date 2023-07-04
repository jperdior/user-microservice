<?php

declare(strict_types=1);

namespace App\UserComponent\Domain\Entity;

interface UniqueIdGeneratorInterface
{
    public function generateUlid(): string;
}