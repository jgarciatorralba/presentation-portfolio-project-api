<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use App\Shared\Utils;
use Stringable;

readonly class Url implements Stringable
{
    /**
     * @throws \InvalidArgumentException
     */
    private function __construct(private string $value)
    {
        $this->validate();
    }

    /**
     * @throws \InvalidArgumentException
     */
    public static function fromString(string $value): self
    {
        return new self($value);
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

    /**
     * @throws \InvalidArgumentException
     */
    private function validate(): void
    {
        if (filter_var($this->value, FILTER_VALIDATE_URL) === false) {
            throw new \InvalidArgumentException(
                sprintf(
                    "'%s' does not allow the value '%s'.",
                    Utils::extractClassName(Url::class),
                    $this->value
                )
            );
        }
    }
}
