<?php

declare(strict_types=1);

namespace App\Projects\Domain\ValueObject;

use App\Projects\Domain\Exception\InvalidProjectIdException;
use App\Shared\Domain\Contract\Comparable;
use Stringable;

final readonly class ProjectId implements Stringable, Comparable
{
    private function __construct(
        private int $value
    ) {
    }

    /** @throws InvalidProjectIdException */
    public static function create(int $value): self
    {
        if ($value < 1) {
            throw new InvalidProjectIdException($value);
        }

        return new self($value);
    }

    public function value(): int
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

    public function equals(Comparable $projectId): bool
    {
        if (!$projectId instanceof self) {
            return false;
        }

        return $this->value === $projectId->value;
    }
}
