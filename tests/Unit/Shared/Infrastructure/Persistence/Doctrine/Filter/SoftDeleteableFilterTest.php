<?php

declare(strict_types=1);

namespace Tests\Unit\Shared\Infrastructure\Persistence\Doctrine\Filter;

use App\Shared\Infrastructure\Persistence\Doctrine\Filter\SoftDeleteableFilter;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;

final class SoftDeleteableFilterTest extends TestCase
{
    private Stub&EntityManagerInterface $entityManager;
    private ?string $tableAlias;

    protected function setUp(): void
    {
        $this->entityManager = $this->createStub(EntityManagerInterface::class);
        $this->tableAlias = 'alias';
    }

    protected function tearDown(): void
    {
        $this->tableAlias = null;
    }

    public function testItAddsFilterConstraint(): void
    {
        $filter = new SoftDeleteableFilter(
            em: $this->entityManager
        );

        $metadata = $this->createStub(ClassMetadata::class);

        $result = $filter->addFilterConstraint($metadata, $this->tableAlias);
        $this->assertEquals("{$this->tableAlias}.deleted_at IS NULL", $result);
    }
}
