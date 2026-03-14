<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use App\Shared\Domain\Contract\Comparable;
use App\Shared\Domain\Exception\InvalidCodeRepositoryUrlException;

abstract readonly class CodeRepository implements Comparable
{
    protected const string DOMAIN = '';

    /**
     * @throws InvalidCodeRepositoryUrlException
     */
    final private function __construct(protected Url $url)
    {
        $this->validate();
    }

    /**
     * @throws \InvalidArgumentException
     * @throws InvalidCodeRepositoryUrlException
     */
    public static function fromUrlValue(string $url): static
    {
        $url = Url::fromString($url);

        return new static($url);
    }

    public function urlValue(): string
    {
        return $this->url->value();
    }

    public function equals(Comparable $other): bool
    {
        if (!$other instanceof static) {
            return false;
        }

        return $this->url->value() === $other->url->value();
    }

    /**
     * @throws InvalidCodeRepositoryUrlException
     */
    private function validate(): void
    {
        $host = parse_url($this->url->value(), PHP_URL_HOST);

        if (!is_string($host) || !str_contains($host, static::DOMAIN)) {
            throw new InvalidCodeRepositoryUrlException(
                $this->url->value(),
                static::DOMAIN,
            );
        }
    }
}
