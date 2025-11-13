<?php

declare(strict_types=1);

namespace Tests\Support\Builder\Shared\Domain\Http;

use App\Shared\Domain\Http\HttpHeader;
use Tests\Support\Builder\BuilderInterface;
use Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;

final class HttpHeaderBuilder implements BuilderInterface
{
    private const int MIN_VALUES = 1;
    private const int MAX_VALUES = 10;

    /** @var string[] $values */
    private array $values;

    private function __construct(
        private string $name,
        string ...$values
    ) {
        $this->values = $values;
    }

    public static function any(): self
    {
        return new self(
            FakeValueGenerator::string(),
            ...self::randomValues(),
        );
    }

    /** @throws \InvalidArgumentException */
    public function build(): HttpHeader
    {
        return new HttpHeader(
            $this->name,
            ...$this->values,
        );
    }

    public function withName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /** @return string[] */
    private static function randomValues(?int $numValues = null): array
    {
        if ($numValues === null) {
            $numValues = FakeValueGenerator::integer(
                min: self::MIN_VALUES,
                max: self::MAX_VALUES
            );
        }

        $values = [];
        for ($i = 0; $i < $numValues; $i++) {
            $values[] = FakeValueGenerator::string();
        }

        return $values;
    }
}
