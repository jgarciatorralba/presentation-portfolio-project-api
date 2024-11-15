<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Doctrine;

use App\Shared\Domain\Criteria\Criteria;
use App\Shared\Domain\Criteria\Filter\CompositeFilter;
use App\Shared\Domain\Criteria\Filter\SimpleFilter;
use App\Shared\Domain\Criteria\Order\Order;
use Doctrine\Common\Collections\Criteria as DoctrineCriteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\Common\Collections\Expr\CompositeExpression;
use Doctrine\Common\Collections\Expr\Expression;
use Doctrine\Common\Collections\Order as DoctrineCriteriaOrder;

final readonly class DoctrineCriteriaConverter
{
    public function __construct(
        private Criteria $criteria
    ) {
    }

    /**
     * @throws \TypeError
     * @throws \ValueError
     * @throws \RuntimeException
     */
    public static function convert(Criteria $criteria): DoctrineCriteria
    {
        $converter = new self($criteria);

        return $converter->convertToDoctrineCriteria();
    }

    /**
     * @throws \TypeError
     * @throws \ValueError
     * @throws \RuntimeException
     */
    private function convertToDoctrineCriteria(): DoctrineCriteria
    {
        return new DoctrineCriteria(
            $this->buildExpression($this->criteria),
            $this->formatOrder($this->criteria),
            $this->criteria->offset() ?? 0,
            $this->criteria->limit()
        );
    }

    /** @throws \RuntimeException */
    private function buildExpression(Criteria $criteria): ?CompositeExpression
    {
        if (!$criteria->hasFilters()) {
            return null;
        }

        return new CompositeExpression(
            $criteria->filters()->condition()->value,
            array_map(
                $this->buildComparison(),
                $criteria->filters()->filterGroup()
            )
        );
    }

    /** @throws \RuntimeException */
    private function buildComparison(): callable
    {
        return function (SimpleFilter|CompositeFilter $filter): Expression {
            if ($filter instanceof CompositeFilter) {
                return new CompositeExpression(
                    $filter->condition()->value,
                    array_map(
                        $this->buildComparison(),
                        $filter->filters()
                    )
                );
            }

            return new Comparison(
                $filter->field(),
                $filter->operator()->value,
                $filter->value()
            );
        };
    }

    /**
     * @return array<string, DoctrineCriteriaOrder>|null
     *
     * @throws \TypeError
     * @throws \ValueError
     */
    private function formatOrder(Criteria $criteria): ?array
    {
        if (!$criteria->hasOrder()) {
            return null;
        }

        $orderArray = [];

        /** @var Order $order */
        foreach ($criteria->orderBy()->orderings() as $order) {
            $orderArray[$order->field()] = DoctrineCriteriaOrder::from(
                $order->type()->value
            );
        }

        return $orderArray;
    }
}
