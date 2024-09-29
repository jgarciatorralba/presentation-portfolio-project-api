<?php

declare(strict_types=1);

namespace App\Shared\Domain\Contract;

interface Comparable
{
    public function equals(Comparable $object): bool;
}
