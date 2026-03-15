<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use App\Shared\Domain\Contract\Comparable;
use App\Shared\Domain\Exception\InvalidCodeRepositoryUrlException;

abstract readonly class CodeRepository implements Comparable
{
    /**
	 * @throws \InvalidArgumentException
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

	abstract public function domain(): string;

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
	 * @throws \InvalidArgumentException
     * @throws InvalidCodeRepositoryUrlException
     */
    private function validate(): void
    {
        $this->validateDomain();

        $host = parse_url($this->url->value(), PHP_URL_HOST);
        if (!is_string($host) || !str_contains($host, $this->domain())) {
            throw new InvalidCodeRepositoryUrlException(
                $this->url->value(),
                $this->domain(),
            );
        }
    }

	/**
	 * @throws \InvalidArgumentException
	 */
	private function validateDomain(): void
	{
		if ('' === $this->domain()) {
			throw new \InvalidArgumentException('Domain cannot be empty.');
		}

		if (false === filter_var(
			value: $this->domain(),
			filter: FILTER_VALIDATE_DOMAIN,
			options: FILTER_FLAG_HOSTNAME
		)) {
			throw new \InvalidArgumentException(sprintf('Invalid domain: %s', $this->domain()));
		}
	}
}
