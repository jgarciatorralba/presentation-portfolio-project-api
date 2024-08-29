<?php

declare(strict_types=1);

namespace App\Tests\Unit\UI\TestCase;

use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use App\UI\Validation\Validator;

final class ValidatorMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return Validator::class;
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, string> $errors
     */
    public function shouldValidate(
        array $data,
        array $errors
    ): void {
        $this->mock
        ->expects($this->once())
        ->method('validate')
        ->with($data)
        ->willReturn($errors);
    }
}
