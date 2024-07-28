<?php

declare(strict_types=1);

namespace App\Shared\Domain\Criteria\Filter;

use App\Shared\Domain\Trait\EnumValuesProviderTrait;

enum FilterConditionEnum: string
{
    use EnumValuesProviderTrait;

    case AND = 'AND';
    case OR = 'OR';
}
