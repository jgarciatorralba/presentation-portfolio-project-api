<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\TestCase;

use App\Projects\Domain\Project;
use App\Projects\Domain\Service\GetProjectsByCriteria;
use App\Shared\Domain\Criteria\Criteria;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\InvalidArgumentException;
use PHPUnit\Framework\MockObject\MethodCannotBeConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameAlreadyConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameNotConfiguredException;
use PHPUnit\Framework\MockObject\MethodParametersAlreadyConfiguredException;

final class GetProjectsByCriteriaMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return GetProjectsByCriteria::class;
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     * @throws MethodNameNotConfiguredException
     * @throws MethodParametersAlreadyConfiguredException
     * @throws \InvalidArgumentException
     */
    public function shouldGetProjects(object $criteria, Project ...$projects): void
    {
        if (!$criteria instanceof Criteria) {
            throw new \InvalidArgumentException('Expected instance of Criteria or its subclasses');
        }

        $this->mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($this->anything())
            ->willReturn($projects);
    }
}
