<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\Criteria;

use App\Shared\Domain\Criteria\CreatedBeforeDateTimeCriteria;
use App\Tests\Unit\Shared\Domain\Criteria\Factory\CreatedBeforeDateTimeCriteriaFactory;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class CreatedBeforeDateTimeCriteriaTest extends TestCase
{
    #[DataProvider('dataCreatedBeforeDateTimeCriteria')]
    public function testCriteriaIsCreated(
        \DateTimeImmutable $maxCreatedAt,
        ?int $limit
    ): void {
        $createdBeforeDateTimeCriteriaCreated = CreatedBeforeDateTimeCriteriaFactory::create(
            $maxCreatedAt,
            $limit
        );

        $createdBeforeDateTimeCriteriaAsserted = new CreatedBeforeDateTimeCriteria(
            maxCreatedAt: $maxCreatedAt,
            limit: $limit
        );

        $this->assertEquals(
            $createdBeforeDateTimeCriteriaCreated,
            $createdBeforeDateTimeCriteriaAsserted
        );
    }

    /**
     * @return array<array{
     *      maxCreatedAt: \DateTimeImmutable,
     *      limit: int|null
     * }>
     */
    public static function dataCreatedBeforeDateTimeCriteria(): array
    {
        return [
            'current DateTime and given limit' => [
                'maxCreatedAt' => new \DateTimeImmutable(),
                'limit' => 10
            ],
            'given DateTime and no limit' => [
                'maxCreatedAt' => new \DateTimeImmutable('2022-01-01 00:00:00'),
                'limit' => null
            ]
        ];
    }
}
