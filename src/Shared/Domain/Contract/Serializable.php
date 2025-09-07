<?php

declare(strict_types=1);

namespace App\Shared\Domain\Contract;

interface Serializable
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
