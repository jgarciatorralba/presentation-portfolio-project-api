<?php

declare(strict_types=1);

namespace App\Shared\Domain\Contract;

/**
 * @template T
 */
interface Collection
{
    /** @return list<T> */
    public function all(): array;

    public function has(string $key): bool;

    /** @return T|null */
    public function get(string $key): mixed;
}
