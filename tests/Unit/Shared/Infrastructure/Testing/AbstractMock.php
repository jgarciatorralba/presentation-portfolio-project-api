<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Infrastructure\Testing;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

abstract class AbstractMock extends TestCase
{
    protected MockObject $mock;

    public function __construct()
    {
        /** @var class-string $className */
        $className = $this->getClassName();
        $this->mock = $this->getMockBuilder($className)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function getMock(): MockObject
    {
        return $this->mock;
    }

    abstract protected function getClassName(): string;
}
