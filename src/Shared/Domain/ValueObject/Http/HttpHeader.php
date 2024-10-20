<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject\Http;

final readonly class HttpHeader
{
    private const string VALID_NAME_REGEX = '/^[a-zA-Z0-9\-]+$/';

    /** @var string[] */
    private array $values;

    public function __construct(
        private string $name,
        string ...$values,
    ) {
        if (!preg_match(self::VALID_NAME_REGEX, $name)) {
            throw new \InvalidArgumentException('Invalid header name');
        }

        $this->values = $values;
    }

    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return string[]
     */
    public function values(): array
    {
        return $this->values;
    }
}
