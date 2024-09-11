<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\Criteria;

use App\Shared\Domain\Criteria\UpdatedBeforeDateTimeCriteria;
use App\Tests\Unit\Shared\Domain\Criteria\Factory\UpdatedBeforeDateTimeCriteriaFactory;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class UpdatedBeforeDateTimeCriteriaTest extends TestCase
{
    #[DataProvider('dataUpdatedBeforeDateTimeCriteria')]
    public function testCriteriaIsCreated(
        \DateTimeImmutable $maxUpdatedAt,
        ?int $limit
    ): void {
        $expectedUpdatedBeforeDateTimeCriteria = UpdatedBeforeDateTimeCriteriaFactory::create(
            $maxUpdatedAt,
            $limit
        );

        $actualUpdatedBeforeDateTimeCriteria = new UpdatedBeforeDateTimeCriteria(
            maxUpdatedAt: $maxUpdatedAt,
            limit: $limit
        );

        $this->assertEquals(
            $expectedUpdatedBeforeDateTimeCriteria,
            $actualUpdatedBeforeDateTimeCriteria
        );
    }

    /**
     * @return array<array{
     *      maxUpdatedAt: \DateTimeImmutable,
     *      limit: int|null
     * }>
     */
    public static function dataUpdatedBeforeDateTimeCriteria(): array
    {
        return [
            'current DateTime and given limit' => [
                'maxUpdatedAt' => new \DateTimeImmutable(),
                'limit' => 10
            ],
            'given DateTime and no limit' => [
                'maxUpdatedAt' => new \DateTimeImmutable('2022-01-01 00:00:00'),
                'limit' => null
            ]
        ];
    }
}
