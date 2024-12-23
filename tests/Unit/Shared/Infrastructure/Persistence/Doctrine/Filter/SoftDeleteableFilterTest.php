<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Infrastructure\Persistence\Doctrine\Filter;

use App\Shared\Infrastructure\Persistence\Doctrine\Filter\SoftDeleteableFilter;
use App\Tests\Unit\Shared\TestCase\EntityManagerMock;
use Doctrine\ORM\Mapping\ClassMetadata;
use PHPUnit\Framework\TestCase;

final class SoftDeleteableFilterTest extends TestCase
{
    private ?EntityManagerMock $entityManager;
    private ?string $tableAlias;

    protected function setUp(): void
    {
        $this->entityManager = new EntityManagerMock($this);
        $this->tableAlias = 'alias';
    }

    protected function tearDown(): void
    {
        $this->entityManager = null;
        $this->tableAlias = null;
    }

    public function testItAddsFilterConstraint(): void
    {
        $filter = new SoftDeleteableFilter(
            em: $this->entityManager->getMock()
        );

        $metadata = $this->createMock(ClassMetadata::class);

        $result = $filter->addFilterConstraint($metadata, $this->tableAlias);
        $this->assertEquals("{$this->tableAlias}.deleted_at IS NULL", $result);
    }
}
