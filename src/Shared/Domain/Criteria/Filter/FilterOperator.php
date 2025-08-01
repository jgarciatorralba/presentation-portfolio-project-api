<?php

declare(strict_types=1);

namespace App\Shared\Domain\Criteria\Filter;

use App\Shared\Domain\Trait\EnumValuesProvider;

enum FilterOperator: string
{
    use EnumValuesProvider;

    case EQUAL = '=';
    case NOT_EQUAL = '!=';
    case LOWER_THAN = '<';
    case LOWER_THAN_OR_EQUAL = '<=';
    case GREATER_THAN = '>';
    case GREATER_THAN_OR_EQUAL = '>=';
}
