<?php

declare(strict_types=1);

namespace App\Projects\Domain\ValueObject;

use App\Projects\Domain\Exception\InvalidProjectRepositoryUrlException;
use App\Shared\Domain\ValueObject\Url;
use Stringable;

final readonly class ProjectRepositoryUrl extends Url implements Stringable
{
    private const string GITHUB_DOMAIN = 'github.com';

    private function __construct(string $value)
    {
        parent::__construct($value);
    }

    /**
     * @throws \InvalidArgumentException
     * @throws InvalidProjectRepositoryUrlException
     */
    public static function fromString(string $value): static
    {
        $url = Url::fromString($value);

        $host = parse_url($url->value(), PHP_URL_HOST);
        if (!str_contains($host, self::GITHUB_DOMAIN)) {
            throw new InvalidProjectRepositoryUrlException($value);
        }

        return new static($url->value());
    }

    public function __toString(): string
    {
        return $this->value();
    }
}
