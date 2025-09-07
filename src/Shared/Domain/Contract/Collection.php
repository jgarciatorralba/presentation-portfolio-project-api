<?php

declare(strict_types=1);

namespace App\Shared\Domain\Contract;

/**
 * @template T
 * @extends \IteratorAggregate<string, T>
 */
interface Collection extends \IteratorAggregate
{
    public function has(string $key): bool;

    /** @return T|null */
    public function get(string $key): mixed;
}
