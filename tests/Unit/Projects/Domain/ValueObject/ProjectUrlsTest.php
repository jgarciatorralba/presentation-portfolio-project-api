<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Domain\ValueObject;

use App\Projects\Domain\ValueObject\ProjectUrls;
use App\Tests\Support\Builder\Projects\Domain\ProjectBuilder;
use App\Tests\Support\Builder\Projects\Domain\ValueObject\ProjectUrlsBuilder;
use App\Tests\Support\Builder\Shared\Domain\ValueObject\UrlBuilder;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ProjectUrlsTest extends TestCase
{
    public function testTheyAreCreated(): void
    {
        $expected = ProjectUrlsBuilder::any()->build();

        $actual = ProjectUrls::create(
            repository: $expected->repository(),
            homepage: $expected->homepage()
        );

        $this->assertEquals($expected, $actual);
    }

    #[DataProvider('dataProjectUrls')]
    public function testTheyAreComparable(
        ProjectUrls $projectUrls,
        ProjectUrls $otherProjectUrls,
        bool $areEqual
    ): void {
        $this->assertEquals($areEqual, $projectUrls->equals($otherProjectUrls));
    }

    /**
     * @return array<string, array{0: ProjectUrls, 1: ProjectUrls, 2: bool}>
     */
    public static function dataProjectUrls(): array
    {
        $projectUrls = ProjectUrlsBuilder::any()->build();

        $sameProjectUrls = ProjectUrlsBuilder::any()
            ->withRepository($projectUrls->repository())
            ->withHomepage($projectUrls->homepage())
            ->build();

        $differentProjectUrls = ProjectUrlsBuilder::any()
            ->withHomepage(UrlBuilder::any()->build())
            ->build();

        return [
            'same project urls' => [$projectUrls, $sameProjectUrls, true],
            'different project urls' => [$projectUrls, $differentProjectUrls, false],
        ];
    }

    public function testTheyAreComparableToDifferentClass(): void
    {
        $project = ProjectBuilder::any()->build();
        $projectUrls = ProjectUrlsBuilder::any()->build();

        $this->assertFalse($projectUrls->equals($project));
    }
}
