<?php

declare(strict_types=1);

namespace App\Tests\Unit\UI\TestCase;

use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use App\UI\Validation\Validator;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\InvalidArgumentException;
use PHPUnit\Framework\MockObject\IncompatibleReturnValueException;
use PHPUnit\Framework\MockObject\MethodCannotBeConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameAlreadyConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameNotConfiguredException;
use PHPUnit\Framework\MockObject\MethodParametersAlreadyConfiguredException;

final class ValidatorMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return Validator::class;
    }

    /**
     * @throws Exception
     * @throws IncompatibleReturnValueException
     * @throws InvalidArgumentException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     * @throws MethodNameNotConfiguredException
     * @throws MethodParametersAlreadyConfiguredException
     *
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
