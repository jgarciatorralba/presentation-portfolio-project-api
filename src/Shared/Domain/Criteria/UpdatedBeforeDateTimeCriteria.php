<?php

declare(strict_types=1);

namespace App\Shared\Domain\Criteria;

use App\Shared\Domain\Criteria\Filter\FilterCondition;
use App\Shared\Domain\Criteria\Filter\FilterOperator;
use App\Shared\Domain\Criteria\Filter\Filters;
use App\Shared\Domain\Criteria\Filter\SimpleFilter;
use App\Shared\Domain\Criteria\Order\Order;
use App\Shared\Domain\Criteria\Order\OrderBy;
use App\Shared\Domain\Criteria\Order\OrderType;

final readonly class UpdatedBeforeDateTimeCriteria extends Criteria
{
    public function __construct(
        \DateTimeImmutable $maxUpdatedAt,
        ?int $limit = null
    ) {
        parent::__construct(
            filters: new Filters(
                FilterCondition::AND,
                new SimpleFilter('updatedAt', $maxUpdatedAt, FilterOperator::LOWER_THAN)
            ),
            orderBy: new OrderBy(
                new Order('lastPushedAt', OrderType::DESCENDING)
            ),
            limit: $limit
        );
    }
}
