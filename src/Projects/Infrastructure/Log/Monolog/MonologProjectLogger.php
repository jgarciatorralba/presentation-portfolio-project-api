<?php

declare(strict_types=1);

namespace App\Projects\Infrastructure\Log\Monolog;

use App\Shared\Infrastructure\Log\Monolog\MonologLogger;
use Psr\Log\LoggerInterface;

final class MonologProjectLogger extends MonologLogger
{
    public function __construct(
        LoggerInterface $projectLogger
    ) {
        parent::__construct($projectLogger);
    }
}
