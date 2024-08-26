<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Infrastructure\Persistence\Doctrine;

use App\Shared\Domain\Criteria\CreatedBeforeDateTimeCriteria;
use App\Shared\Infrastructure\Persistence\Doctrine\DoctrineCriteriaConverter;
use App\Tests\Unit\Shared\Domain\Criteria\Factory\CriteriaFactory;
use App\Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;
use Doctrine\Common\Collections\Criteria as DoctrineCriteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\Common\Collections\Expr\CompositeExpression;
use Doctrine\Common\Collections\Order;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class DoctrineCriteriaConverterTest extends TestCase
{
    public function testItConvertsRandomCriteria(): void
    {
        $criteria = CriteriaFactory::create();

        $doctrineCriteria = DoctrineCriteriaConverter::convert($criteria);

        $this->assertInstanceof(DoctrineCriteria::class, $doctrineCriteria);
        $this->assertEquals($criteria->limit(), $doctrineCriteria->getMaxResults());
        $this->assertEquals($criteria->offset(), $doctrineCriteria->getFirstResult());
    }

    #[DataProvider('dataCreatedBeforeDateTimeCriteria')]
    public function testItConvertsCreatedBeforeDateTimeCriteria(
        \DateTimeImmutable $maxCreatedAt,
        ?int $limit
    ): void {
        $criteria = new CreatedBeforeDateTimeCriteria(
            maxCreatedAt: $maxCreatedAt,
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
            'createdAt',
            $comparisons[array_key_first($comparisons)]->getField()
        );
        $this->assertEquals(
            Comparison::LT,
            $comparisons[array_key_first($comparisons)]->getOperator()
        );
        $this->assertEquals(
            $maxCreatedAt,
            $comparisons[array_key_first($comparisons)]->getValue()->getValue()
        );

        $this->assertEquals(CompositeExpression::TYPE_AND, $type);

        $this->assertCount(1, $orderings);
        $this->assertEquals('createdAt', array_key_first($orderings));
        $this->assertEquals(Order::Descending, $orderings[array_key_first($orderings)]);
    }

    /**
     * @return array<string, array<DateTimeImmutable, int|null>>
     */
    public static function dataCreatedBeforeDateTimeCriteria(): array
    {
        return [
            'default maxCreatedAt and no limit' => [
                new \DateTimeImmutable(),
                null
            ],
            'default maxCreatedAt and limit' => [
                new \DateTimeImmutable(),
                FakeValueGenerator::integer()
            ],
            'maxCreatedAt and no limit' => [
                FakeValueGenerator::dateTime(),
                null
            ],
            'maxCreatedAt and limit' => [
                FakeValueGenerator::dateTime(),
                FakeValueGenerator::integer()
            ]
        ];
    }
}
