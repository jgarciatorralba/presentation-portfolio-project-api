<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Log\Monolog;

use App\Shared\Domain\Contract\Logger;
use Psr\Log\LoggerInterface;

abstract class MonologLogger implements Logger
{
    public function __construct(
        private readonly LoggerInterface $logger
    ) {
    }

    public function info(
        string $message,
        array $context = []
    ): void {
        $this->logger
            ->info($message, $context);
    }

    public function warning(
        string $message,
        array $context = []
    ): void {
        $this->logger
            ->warning($message, $context);
    }

    public function error(
        string $message,
        array $context = []
    ): void {
        $this->logger
            ->error($message, $context);
    }
}
