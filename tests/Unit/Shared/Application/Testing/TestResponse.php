<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Application\Testing;

use App\Shared\Domain\Bus\Query\Response;

final class TestResponse implements Response
{
    /** @param array<mixed> $data */
    public function __construct(
        private readonly array $data
    ) {
    }

    public function data(): array
    {
        return $this->data;
    }
}
