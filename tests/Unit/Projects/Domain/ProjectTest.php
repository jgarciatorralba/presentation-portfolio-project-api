<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Domain;

use App\Projects\Domain\Project;
use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Utils;
use App\Tests\Support\Builder\Projects\Domain\ProjectBuilder;
use PHPUnit\Framework\TestCase;

final class ProjectTest extends TestCase
{
    private ?Project $expected;

    protected function setUp(): void
    {
        $now = new \DateTimeImmutable();

        $this->expected = ProjectBuilder::any()
            ->withCreatedAt($now)
            ->withUpdatedAt($now)
            ->build();
    }

    protected function tearDown(): void
    {
        $this->expected = null;
    }

    public function testItIsRecreated(): void
    {
        $actual = Project::recreate(
            id: $this->expected->id(),
            details: $this->expected->details(),
            urls: $this->expected->urls(),
            archived: $this->expected->archived(),
            lastPushedAt: $this->expected->lastPushedAt()
        );

        $this->assertProjectsAreEqual($this->expected, $actual);
    }

    public function testItExtendsAggregateRoot(): void
    {
        $this->assertInstanceOf(AggregateRoot::class, $this->expected);

        $this->assertTrue(method_exists($this->expected, 'pullEvents'));
        $this->assertTrue(method_exists($this->expected, 'recordEvent'));
    }

    public function testItIsSerializable(): void
    {
        $reflection = new \ReflectionClass(Utils::class);
        $format = $reflection->getConstant('UTC_DATETIME_STRING_FORMAT');

        $projectArray = $this->expected->toArray();

        $this->assertIsArray($projectArray);
        $this->assertCount(8, array_keys($projectArray));
        $this->assertEquals($this->expected->id()->value(), $projectArray['id']);
        $this->assertEquals($this->expected->details()->name(), $projectArray['name']);
        $this->assertEquals($this->expected->details()->description(), $projectArray['description']);
        $this->assertEquals($this->expected->details()->topics(), $projectArray['topics']);
        $this->assertEquals($this->expected->urls()->repository()->value(), $projectArray['repository']);
        $this->assertEquals($this->expected->urls()->homepage()?->value() ?? null, $projectArray['homepage']);
        $this->assertEquals($this->expected->archived(), $projectArray['archived']);
        $this->assertEquals(
            $this->expected->lastPushedAt()->setTimezone(new \DateTimeZone('UTC'))->format($format),
            $projectArray['lastPushedAt']
        );
    }

    public function testItIsComparable(): void
    {
        $actual = Project::recreate(
            id: $this->expected->id(),
            details: $this->expected->details(),
            urls: $this->expected->urls(),
            archived: $this->expected->archived(),
            lastPushedAt: $this->expected->lastPushedAt()
        );

        $this->assertTrue($this->expected->equals($actual));
    }

    public function testItIsComparableToDifferentClass(): void
    {
        $project = ProjectBuilder::any()->build();
        $details = $project->details();

        $this->assertFalse($this->expected->equals($details));
    }

    public function testItSynchronizesWithAnotherProject(): void
    {
        $project = ProjectBuilder::any()
            ->withId($this->expected->id())
            ->build();

        $project->synchronizeWith($this->expected);

        $this->assertTrue($project->equals($this->expected));
    }

    public function testItThrowsExceptionWhenSynchronizingWithDifferentProjectId(): void
    {
        $project = ProjectBuilder::any()
            ->build();

        $this->expectException(\InvalidArgumentException::class);

        $project->synchronizeWith($this->expected);
    }

    private function assertProjectsAreEqual(Project $expected, Project $actual): void
    {
        $this->assertEquals(
            $expected->id(),
            $actual->id()
        );
        $this->assertEquals(
            $expected->details(),
            $actual->details()
        );
        $this->assertEquals(
            $expected->urls(),
            $actual->urls()
        );
        $this->assertEquals(
            $expected->archived(),
            $actual->archived()
        );
        $this->assertEquals(
            $expected->lastPushedAt(),
            $actual->lastPushedAt()
        );
        $this->assertEquals(
            $expected->deletedAt(),
            $actual->deletedAt()
        );

        $diffCreatedAt = $actual->createdAt()->getTimestamp()
            - $expected->createdAt()->getTimestamp();
        $this->assertLessThanOrEqual(1, $diffCreatedAt);

        $diffUpdatedAt = $actual->updatedAt()->getTimestamp()
            - $expected->updatedAt()->getTimestamp();
        $this->assertLessThanOrEqual(1, $diffUpdatedAt);
    }
}
