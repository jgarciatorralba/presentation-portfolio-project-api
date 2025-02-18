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

    public function alert(
        string|\Stringable $message,
        array $context = []
    ): void {
        $this->logger
            ->alert($message, $context);
    }

    public function critical(
        string|\Stringable $message,
        array $context = []
    ): void {
        $this->logger
            ->critical($message, $context);
    }

    public function debug(
        string|\Stringable $message,
        array $context = []
    ): void {
        $this->logger
            ->debug($message, $context);
    }

    public function emergency(
        string|\Stringable $message,
        array $context = []
    ): void {
        $this->logger
            ->emergency($message, $context);
    }

    public function error(
        string|\Stringable $message,
        array $context = []
    ): void {
        $this->logger
            ->error($message, $context);
    }

    public function info(
        string|\Stringable $message,
        array $context = []
    ): void {
        $this->logger
            ->info($message, $context);
    }

    public function log(
        mixed $level,
        string|\Stringable $message,
        array $context = []
    ): void {
        $this->logger
            ->log($level, $message, $context);
    }

    public function notice(
        string|\Stringable $message,
        array $context = []
    ): void {
        $this->logger
            ->notice($message, $context);
    }

    public function warning(
        string|\Stringable $message,
        array $context = []
    ): void {
        $this->logger
            ->warning($message, $context);
    }
}
