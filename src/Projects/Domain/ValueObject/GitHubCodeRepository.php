<?php

declare(strict_types=1);

namespace App\Projects\Domain\ValueObject;

use App\Projects\Domain\Exception\InvalidCodeRepositoryUrlException;
use App\Shared\Domain\Contract\Comparable;
use App\Shared\Domain\ValueObject\Url;

final readonly class GitHubCodeRepository implements Comparable
{
    private const string DOMAIN = 'github.com';

    /**
     * @throws InvalidCodeRepositoryUrlException
     */
    private function __construct(private Url $url)
    {
        $this->validate();
    }


    /**
     * @throws \InvalidArgumentException
     * @throws InvalidCodeRepositoryUrlException
     */
    public static function fromUrlValue(string $url): self
    {
        $url = Url::fromString($url);

        return new self($url);
    }

    public function urlValue(): string
    {
        return $this->url->value();
    }

    public function equals(Comparable $other): bool
    {
        if (!$other instanceof self) {
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

        if (!is_string($host) || !str_contains($host, self::DOMAIN)) {
            throw new InvalidCodeRepositoryUrlException(
                $this->url->value(),
                self::DOMAIN,
            );
        }
    }
}
