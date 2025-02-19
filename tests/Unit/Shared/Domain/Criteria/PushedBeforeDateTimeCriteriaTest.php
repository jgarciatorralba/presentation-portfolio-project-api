<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\Criteria;

use App\Shared\Domain\Criteria\PushedBeforeDateTimeCriteria;
use App\Tests\Builder\Shared\Domain\Criteria\PushedBeforeDateTimeCriteriaBuilder;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class PushedBeforeDateTimeCriteriaTest extends TestCase
{
    #[DataProvider('dataPushedBeforeDateTimeCriteria')]
    public function testItIsCreated(
        \DateTimeImmutable $maxPushedAt,
        ?int $limit
    ): void {
        $expected = PushedBeforeDateTimeCriteriaBuilder::any()
            ->withMaxPushedAt($maxPushedAt)
            ->withLimit($limit)
            ->build();

        $actual = new PushedBeforeDateTimeCriteria(
            maxPushedAt: $maxPushedAt,
            limit: $limit
        );

        $this->assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @return array<array{
     *      maxPushedAt: \DateTimeImmutable,
     *      limit: int|null
     * }>
     */
    public static function dataPushedBeforeDateTimeCriteria(): array
    {
        return [
            'current DateTime and given limit' => [
                'maxPushedAt' => new \DateTimeImmutable(),
                'limit' => 10
            ],
            'given DateTime and no limit' => [
                'maxPushedAt' => new \DateTimeImmutable('2022-01-01 00:00:00'),
                'limit' => null
            ]
        ];
    }
}
