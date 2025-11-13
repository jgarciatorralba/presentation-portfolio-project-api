<?php

declare(strict_types=1);

namespace Tests\Unit\Projects\Domain\ValueObject;

use App\Projects\Domain\ValueObject\ProjectDetails;
use Tests\Support\Builder\Projects\Domain\ProjectBuilder;
use Tests\Support\Builder\Projects\Domain\ValueObject\ProjectDetailsBuilder;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ProjectDetailsTest extends TestCase
{
    public function testTheyAreCreated(): void
    {
        $expected = ProjectDetailsBuilder::any()->build();

        $actual = ProjectDetails::create(
            name: $expected->name(),
            description: $expected->description(),
            topics: $expected->topics()
        );

        $this->assertEquals($expected, $actual);
    }

    #[DataProvider('dataProjectDetails')]
    public function testTheyAreComparable(
        ProjectDetails $details,
        ProjectDetails $otherDetails,
        bool $areEqual
    ): void {
        $this->assertEquals($areEqual, $details->equals($otherDetails));
    }

    /**
     * @return array<string, array{0: ProjectDetails, 1: ProjectDetails, 2: bool}>
     */
    public static function dataProjectDetails(): array
    {
        $details = ProjectDetailsBuilder::any()->build();

        $sameDetails = ProjectDetailsBuilder::any()
            ->withName($details->name())
            ->withDescription($details->description())
            ->withTopics($details->topics())
            ->build();

        $differentTopics = array_map(
            fn (string $topic): string => $topic . '_different',
            $details->topics()
        );

        $sameDetailsWithDifferentTopics = ProjectDetailsBuilder::any()
            ->withName($details->name())
            ->withDescription($details->description())
            ->withTopics($differentTopics)
            ->build();

        $differentDetails = ProjectDetailsBuilder::any()->build();

        $detailsWithNoTopics = ProjectDetailsBuilder::any()
            ->withTopics(null)
            ->build();

        $sameDetailsWithNoTopics = ProjectDetailsBuilder::any()
            ->withName($detailsWithNoTopics->name())
            ->withDescription($detailsWithNoTopics->description())
            ->withTopics($detailsWithNoTopics->topics())
            ->build();

        return [
            'same project details' => [$details, $sameDetails, true],
            'same project details with different topics' => [$details, $sameDetailsWithDifferentTopics, false],
            'same project details with no topics' => [$detailsWithNoTopics, $sameDetailsWithNoTopics, true],
            'different project details' => [$details, $differentDetails, false],
        ];
    }

    public function testTheyAreComparableToDifferentClass(): void
    {
        $project = ProjectBuilder::any()->build();
        $details = ProjectDetailsBuilder::any()->build();

        $this->assertFalse($details->equals($project));
    }
}
