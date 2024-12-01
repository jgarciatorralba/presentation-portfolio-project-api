<?php

declare(strict_types=1);

namespace App\Shared\Domain\Criteria\Order;

use App\Shared\Domain\Trait\EnumValuesProvider;

enum OrderType: string
{
    use EnumValuesProvider;

    case ASCENDING = 'ASC';
    case DESCENDING = 'DESC';
}
