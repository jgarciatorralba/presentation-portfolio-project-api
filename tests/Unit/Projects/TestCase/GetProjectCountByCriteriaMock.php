<?php

declare(strict_types=1);

namespace Tests\Unit\Projects\TestCase;

use App\Projects\Domain\Service\GetProjectCountByCriteria;
use App\Shared\Domain\Criteria\Criteria;
use Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;

final class GetProjectCountByCriteriaMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return GetProjectCountByCriteria::class;
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function shouldGetProjectCount(object $criteria, int $count): void
    {
        if (!$criteria instanceof Criteria) {
            throw new \InvalidArgumentException('Expected instance of Criteria or its subclasses');
        }

        $this->mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($this->anything())
            ->willReturn($count);
    }
}
