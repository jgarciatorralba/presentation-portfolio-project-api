<?php

declare(strict_types=1);

namespace Tests\Unit\Shared\Application\Testing;

use App\Shared\Domain\Bus\Query\Response;

final readonly class TestResponse implements Response
{
    /** @param array<mixed> $data */
    public function __construct(
        private array $data
    ) {
    }

    public function data(): array
    {
        return $this->data;
    }
}
