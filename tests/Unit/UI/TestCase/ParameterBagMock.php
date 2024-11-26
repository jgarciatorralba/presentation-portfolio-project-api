<?php

declare(strict_types=1);

namespace App\Tests\Unit\UI\TestCase;

use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\InvalidArgumentException;
use PHPUnit\Framework\MockObject\IncompatibleReturnValueException;
use PHPUnit\Framework\MockObject\MethodCannotBeConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameAlreadyConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameNotConfiguredException;
use PHPUnit\Framework\MockObject\MethodParametersAlreadyConfiguredException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class ParameterBagMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return ParameterBagInterface::class;
    }

    /**
     * @throws Exception
     * @throws IncompatibleReturnValueException
     * @throws InvalidArgumentException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     * @throws MethodNameNotConfiguredException
     * @throws MethodParametersAlreadyConfiguredException
     */
    public function shouldGetBaseUrl(string $baseUrl): self
    {
        $this->mock
            ->expects($this->once())
            ->method('get')
            ->with('base_url')
            ->willReturn($baseUrl);

        return $this;
    }
}
