<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use App\Shared\Utils;
use Stringable;
use Symfony\Component\Uid\Uuid as SymfonyUuid;

final readonly class Uuid implements Stringable
{
    /** @throws \InvalidArgumentException */
    public function __construct(
        private string $value
    ) {
        $this->ensureIsValidUuid($value);
    }

    public function equals(Uuid $other): bool
    {
        return $this->value() === $other->value();
    }

    public function value(): string
    {
        return $this->value;
    }

    #[\Override]
    public function __toString(): string
    {
        return $this->value();
    }

    /** @throws \InvalidArgumentException */
    private function ensureIsValidUuid(string $id): void
    {
        if (!SymfonyUuid::isValid($id)) {
            throw new \InvalidArgumentException(sprintf(
                "'%s' does not allow the value '%s'.",
                Utils::extractClassName(Uuid::class),
                $id
            ));
        }
    }

    /** @throws \InvalidArgumentException */
    public static function fromString(string $value): self
    {
        return new self($value);
    }

    /** @throws \InvalidArgumentException */
    public static function random(): self
    {
        return new self(SymfonyUuid::v4()->toRfc4122());
    }
}
