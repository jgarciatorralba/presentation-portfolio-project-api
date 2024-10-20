<?php

declare(strict_types=1);

namespace App\Shared\Domain\Trait;

trait EnumValuesProviderTrait
{
    /** @return string[]|int[] */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
