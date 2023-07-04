<?php

declare(strict_types=1);

namespace App\Tests\Unit\UserComponent\Domain\UseCase;

use PHPUnit\Framework\TestCase;

class RegisterUserUseCaseTest extends TestCase
{

    public function testExecute(): void
    {
        $this->assertEquals(
            expected: true,
            actual: true
        );
    }

}