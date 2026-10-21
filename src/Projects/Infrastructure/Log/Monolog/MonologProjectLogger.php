<?php

declare(strict_types=1);

namespace App\Projects\Infrastructure\Log\Monolog;

use App\Shared\Infrastructure\Log\Monolog\MonologLogger;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Target;

final readonly class MonologProjectLogger extends MonologLogger
{
    public function __construct(
        #[Target('projectLogger')]
        LoggerInterface $logger
    ) {
        parent::__construct($logger);
    }
}
