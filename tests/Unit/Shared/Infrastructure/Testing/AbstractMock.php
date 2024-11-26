<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Infrastructure\Testing;

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
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

abstract class AbstractMock extends TestCase
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
