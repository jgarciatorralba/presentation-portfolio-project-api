<?php

declare(strict_types=1);

namespace App\Tests\Support\Builder;

interface BuilderInterface
{
    public static function any(): self;

    public function build(): object;
}
