<?php

declare(strict_types=1);

namespace App\Shared\Application\Bus\Exception;

use App\Shared\Domain\Bus\Query\Query;
use App\Shared\Utils;

class QueryNotRegisteredException extends \Exception
{
    public function __construct(Query $query)
    {
        $message = sprintf(
            'Query with class %s has no handler registered',
            Utils::extractClassName($query::class)
        );

        parent::__construct($message);
    }
}
