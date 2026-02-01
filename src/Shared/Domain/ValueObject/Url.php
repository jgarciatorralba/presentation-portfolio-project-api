<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use App\Shared\Utils;
use Stringable;

readonly class Url implements Stringable
{
    protected function __construct(
        private string $value
    ) {
    }

    public function value(): string
    {
        return $this->value;
    }

    /** @throws \InvalidArgumentException */
    public static function fromString(string $value): self
    {
        if (filter_var($value, FILTER_VALIDATE_URL) === false) {
            throw new \InvalidArgumentException(
                sprintf(
                    "'%s' does not allow the value '%s'.",
                    Utils::extractClassName(Url::class),
                    $value
                )
            );
        }

        return new self($value);
    }

    #[\Override]
    public function __toString(): string
    {
        return $this->value();
    }
}
