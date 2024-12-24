<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Infrastructure\Testing;

use PHPUnit\Framework\Constraint\IsAnything;
use PHPUnit\Framework\InvalidArgumentException;
use PHPUnit\Framework\MockObject\Generator\ClassIsEnumerationException;
use PHPUnit\Framework\MockObject\Generator\ClassIsFinalException;
use PHPUnit\Framework\MockObject\Generator\DuplicateMethodException;
use PHPUnit\Framework\MockObject\Generator\InvalidMethodNameException;
use PHPUnit\Framework\MockObject\Generator\NameAlreadyInUseException;
use PHPUnit\Framework\MockObject\Generator\OriginalConstructorInvocationRequiredException;
use PHPUnit\Framework\MockObject\Generator\ReflectionException;
use PHPUnit\Framework\MockObject\Generator\RuntimeException;
use PHPUnit\Framework\MockObject\Generator\UnknownTypeException;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Rule\InvokedCount as InvokedCountMatcher;
use PHPUnit\Framework\TestCase;

abstract class AbstractMock
{
    protected MockObject $mock;

    /**
     * @throws ClassIsEnumerationException
     * @throws ClassIsFinalException
     * @throws DuplicateMethodException
     * @throws InvalidArgumentException
     * @throws InvalidMethodNameException
     * @throws NameAlreadyInUseException
     * @throws OriginalConstructorInvocationRequiredException
     * @throws ReflectionException
     * @throws RuntimeException
     * @throws UnknownTypeException
     */
    public function __construct(protected readonly TestCase $testCase)
    {
        /** @var class-string $className */
        $className = $this->getClassName();
        $mockBuilder = new MockBuilder($testCase, $className);

        $this->mock = $mockBuilder
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function getMock(): MockObject
    {
        return $this->mock;
    }

    final protected function anything(): IsAnything
    {
        return new IsAnything();
    }

    final protected function once(): InvokedCountMatcher
    {
        return new InvokedCountMatcher(1);
    }

    final protected function exactly(int $count): InvokedCountMatcher
    {
        return new InvokedCountMatcher($count);
    }

    abstract protected function getClassName(): string;
}
