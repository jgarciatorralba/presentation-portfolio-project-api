<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Infrastructure\Persistence\Doctrine;

use App\Shared\Domain\Criteria\Criteria;
use App\Shared\Domain\Criteria\UpdatedBeforeDateTimeCriteria;
use App\Shared\Infrastructure\Persistence\Doctrine\DoctrineCriteriaConverter;
use App\Tests\Builder\Shared\Domain\Criteria\CriteriaBuilder;
use App\Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;
use Doctrine\Common\Collections\Criteria as DoctrineCriteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\Common\Collections\Expr\CompositeExpression;
use Doctrine\Common\Collections\Order;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class DoctrineCriteriaConverterTest extends TestCase
{
    #[DataProvider('dataCriteria')]
    public function testItConvertsCriteria(
        Criteria $criteria
    ): void {
        $doctrineCriteria = DoctrineCriteriaConverter::convert($criteria);

        $this->assertInstanceof(DoctrineCriteria::class, $doctrineCriteria);
        $this->assertEquals($criteria->limit(), $doctrineCriteria->getMaxResults());
        $this->assertEquals($criteria->offset(), $doctrineCriteria->getFirstResult());
    }

    /**
     * @return array<string, array<Criteria>>
     */
    public static function dataCriteria(): array
    {
        return [
            'no order' => [
                CriteriaBuilder::any()->withOrderBy(null)->build()
            ],
            'no filters' => [
                CriteriaBuilder::any()->withFilters(null)->build()
            ],
            'random criteria' => [
                CriteriaBuilder::any()->build()
            ]
        ];
    }

    #[DataProvider('dataUpdatedBeforeDateTimeCriteria')]
    public function testItConvertsUpdatedBeforeDateTimeCriteria(
        \DateTimeImmutable $maxUpdatedAt,
        ?int $limit
    ): void {
        $criteria = new UpdatedBeforeDateTimeCriteria(
            maxUpdatedAt: $maxUpdatedAt,
            limit: $limit
        );

        $doctrineCriteria = DoctrineCriteriaConverter::convert($criteria);
        /** @var CompositeExpression $expression */
        $expression = $doctrineCriteria->getWhereExpression();
        /** @var Comparison[] $comparisons */
        $comparisons = $expression->getExpressionList();
        $type = $expression->getType();
        $orderings = $doctrineCriteria->orderings();

        $this->assertCount(1, $comparisons);
        $this->assertEquals(
            'updatedAt',
            $comparisons[array_key_first($comparisons)]->getField()
        );
        $this->assertEquals(
            Comparison::LT,
            $comparisons[array_key_first($comparisons)]->getOperator()
        );
        $this->assertEquals(
            $maxUpdatedAt,
            $comparisons[array_key_first($comparisons)]->getValue()->getValue()
        );

        $this->assertEquals(CompositeExpression::TYPE_AND, $type);

        $this->assertCount(1, $orderings);
        $this->assertEquals('lastPushedAt', array_key_first($orderings));
        $this->assertEquals(Order::Descending, $orderings[array_key_first($orderings)]);
    }

    /**
     * @return array<string, array<DateTimeImmutable, int|null>>
     */
    public static function dataUpdatedBeforeDateTimeCriteria(): array
    {
        return [
            'default maxUpdatedAt and no limit' => [
                new \DateTimeImmutable(),
                null
            ],
            'default maxUpdatedAt and limit' => [
                new \DateTimeImmutable(),
                FakeValueGenerator::integer()
            ],
            'maxUpdatedAt and no limit' => [
                FakeValueGenerator::dateTime(),
                null
            ],
            'maxUpdatedAt and limit' => [
                FakeValueGenerator::dateTime(),
                FakeValueGenerator::integer()
            ]
        ];
    }
}
