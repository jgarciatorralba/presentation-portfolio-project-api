<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\TestCase;

use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\InvalidArgumentException;
use PHPUnit\Framework\MockObject\MethodCannotBeConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameAlreadyConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameNotConfiguredException;
use PHPUnit\Framework\MockObject\MethodParametersAlreadyConfiguredException;

final class EntityManagerMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return EntityManagerInterface::class;
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     * @throws MethodNameNotConfiguredException
     * @throws MethodParametersAlreadyConfiguredException
     */
    public function shouldPersistEntity(object $entity): void
    {
        $this->mock
            ->expects($this->once())
            ->method('persist')
            ->with($entity);

        $this->mock
            ->expects($this->once())
            ->method('flush');
    }

    /**
     * @throws InvalidArgumentException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     */
    public function shouldUpdateEntity(): void
    {
        $this->mock
            ->expects($this->once())
            ->method('flush');
    }
}
