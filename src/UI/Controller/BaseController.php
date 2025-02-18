<?php

declare(strict_types=1);

namespace App\UI\Controller;

use App\Shared\Domain\Bus\Query\Query;
use App\Shared\Domain\Bus\Query\QueryBus;
use App\Shared\Domain\Bus\Query\Response;

abstract readonly class BaseController
{
    public function __construct(private QueryBus $queryBus)
    {
    }

    protected function ask(Query $query): ?Response
    {
        return $this->queryBus->ask($query);
    }
}
