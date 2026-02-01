<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Log\Monolog;

use App\Shared\Domain\Contract\Log\Logger;
use Psr\Log\LoggerInterface;

abstract readonly class MonologLogger implements Logger
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    #[\Override]
    public function alert(
        string|\Stringable $message,
        array $context = []
    ): void {
        $this->logger
            ->alert($message, $context);
    }

    #[\Override]
    public function critical(
        string|\Stringable $message,
        array $context = []
    ): void {
        $this->logger
            ->critical($message, $context);
    }

    #[\Override]
    public function debug(
        string|\Stringable $message,
        array $context = []
    ): void {
        $this->logger
            ->debug($message, $context);
    }

    #[\Override]
    public function emergency(
        string|\Stringable $message,
        array $context = []
    ): void {
        $this->logger
            ->emergency($message, $context);
    }

    #[\Override]
    public function error(
        string|\Stringable $message,
        array $context = []
    ): void {
        $this->logger
            ->error($message, $context);
    }

    #[\Override]
    public function info(
        string|\Stringable $message,
        array $context = []
    ): void {
        $this->logger
            ->info($message, $context);
    }

    #[\Override]
    public function log(
        mixed $level,
        string|\Stringable $message,
        array $context = []
    ): void {
        $this->logger
            ->log($level, $message, $context);
    }

    #[\Override]
    public function notice(
        string|\Stringable $message,
        array $context = []
    ): void {
        $this->logger
            ->notice($message, $context);
    }

    #[\Override]
    public function warning(
        string|\Stringable $message,
        array $context = []
    ): void {
        $this->logger
            ->warning($message, $context);
    }
}
