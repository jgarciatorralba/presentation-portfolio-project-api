<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\TestCase;

use App\Projects\Domain\MappedProjects;
use App\Projects\Domain\Project;
use App\Projects\Domain\Service\RequestExternalProjects;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use PHPUnit\Framework\InvalidArgumentException;
use PHPUnit\Framework\MockObject\IncompatibleReturnValueException;
use PHPUnit\Framework\MockObject\MethodCannotBeConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameAlreadyConfiguredException;

final class RequestExternalProjectsMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return RequestExternalProjects::class;
    }

    /**
     * @throws IncompatibleReturnValueException
     * @throws InvalidArgumentException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     */
    public function shouldRequestExternalProjects(Project ...$projects): void
    {
        $this->mock
            ->expects($this->once())
            ->method('__invoke')
            ->willReturn(new MappedProjects(...$projects));
    }
}
