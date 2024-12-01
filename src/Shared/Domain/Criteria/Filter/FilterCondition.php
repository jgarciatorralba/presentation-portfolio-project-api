<?php

declare(strict_types=1);

namespace App\Shared\Domain\Criteria\Filter;

use App\Shared\Domain\Trait\EnumValuesProvider;

enum FilterCondition: string
{
    use EnumValuesProvider;

    case AND = 'AND';
    case OR = 'OR';
}
