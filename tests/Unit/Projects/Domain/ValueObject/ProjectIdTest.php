<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Domain\ValueObject;

use App\Projects\Domain\ValueObject\ProjectId;
use App\Tests\Builder\Projects\Domain\ProjectBuilder;
use App\Tests\Builder\Projects\Domain\ValueObject\ProjectIdBuilder;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ProjectIdTest extends TestCase
{
    public function testItIsCreated(): void
    {
        $expected = ProjectIdBuilder::any()->build();

        $actual = ProjectId::create(
            value: $expected->value()
        );

        $this->assertEquals($expected, $actual);
    }

    public function testItThrowsExceptionWhenValueIsLessThanOne(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        ProjectId::create(value: 0);
    }

    #[DataProvider('dataProjectIds')]
    public function testItIsComparable(
        ProjectId $projectId,
        ProjectId $otherProjectId,
        bool $areEqual
    ): void {
        $this->assertEquals($areEqual, $projectId->equals($otherProjectId));
    }

    /**
     * @return array<string, array{0: ProjectId, 1: ProjectId, 2: bool}>
     */
    public static function dataProjectIds(): array
    {
        $projectId = ProjectIdBuilder::any()->build();
        $sameProjectId = ProjectIdBuilder::any()
            ->withValue($projectId->value())
            ->build();

        $differentProjectId = ProjectIdBuilder::any()->build();

        return [
            'same project ids' => [$projectId, $sameProjectId, true],
            'different project ids' => [$projectId, $differentProjectId, false],
        ];
    }

    public function testItIsComparableToDifferentClass(): void
    {
        $project = ProjectBuilder::any()->build();
        $projectId = ProjectIdBuilder::any()->build();

        $this->assertFalse($projectId->equals($project));
    }

    public function testItConvertsToString(): void
    {
        $projectId = ProjectIdBuilder::any()->build();

        $this->assertEquals((string) $projectId->value(), (string) $projectId);
        $this->assertEquals((string) $projectId->value(), $projectId->__toString());
    }
}
