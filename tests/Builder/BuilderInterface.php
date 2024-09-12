<?php

declare(strict_types=1);

namespace App\Tests\Builder;

interface BuilderInterface
{
    public static function any(): self;

    public function build(): object;
}
