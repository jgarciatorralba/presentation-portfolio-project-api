<?php

declare(strict_types=1);

namespace App\Shared\Domain\Bus\Query;

interface Response
{
    /** @return array<string, mixed> */
    public function data(): array;
}
