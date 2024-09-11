<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Domain;

use App\Projects\Domain\Project;
use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Tests\Unit\Projects\Domain\Factory\ProjectFactory;
use PHPUnit\Framework\TestCase;

final class ProjectTest extends TestCase
{
    private ?Project $expected;

    protected function setUp(): void
    {
        $this->expected = ProjectFactory::create(
            createdAt: new \DateTimeImmutable(),
        );
    }

    protected function tearDown(): void
    {
        $this->expected = null;
    }

    public function testProjectIsCreated(): void
    {
        $actual = Project::create(
            id: $this->expected->id(),
            details: $this->expected->details(),
            urls: $this->expected->urls(),
            archived: $this->expected->archived(),
            lastPushedAt: $this->expected->lastPushedAt()
        );

        $this->assertProjectsAreEqual($this->expected, $actual);
    }

    public function testProjectExtendsAggregateRoot(): void
    {
        $this->assertInstanceOf(AggregateRoot::class, $this->expected);

        $this->assertTrue(method_exists($this->expected, 'pullEvents'));
        $this->assertTrue(method_exists($this->expected, 'recordEvent'));
    }

    public function testProjectIsConvertedToArray(): void
    {
        $projectArray = $this->expected->toArray();

        $this->assertIsArray($projectArray);
        $this->assertCount(8, array_keys($projectArray));
        $this->assertEquals($this->expected->id(), $projectArray['id']);
        $this->assertEquals($this->expected->details()->name(), $projectArray['name']);
        $this->assertEquals($this->expected->details()->description(), $projectArray['description']);
        $this->assertEquals($this->expected->details()->topics(), $projectArray['topics']);
        $this->assertEquals($this->expected->urls()->repository(), $projectArray['repository']);
        $this->assertEquals($this->expected->urls()->homepage(), $projectArray['homepage']);
        $this->assertEquals($this->expected->archived(), $projectArray['archived']);
        $this->assertEquals(
            $this->expected->lastPushedAt()->format(\DateTimeInterface::ATOM),
            $projectArray['lastPushedAt']
        );
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
