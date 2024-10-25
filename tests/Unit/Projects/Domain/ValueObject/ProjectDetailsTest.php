<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Domain\ValueObject;

use App\Projects\Domain\ValueObject\ProjectDetails;
use App\Tests\Builder\Projects\Domain\ValueObject\ProjectDetailsBuilder;
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

        $this->assertEquals($expected, $expected);
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

        $differentDetails = ProjectDetailsBuilder::any()->build();

        return [
            'same project details' => [$details, $sameDetails, true],
            'different project details' => [$details, $differentDetails, false],
        ];
    }
}
