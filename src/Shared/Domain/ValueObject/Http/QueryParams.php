<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject\Http;

use App\Shared\Domain\Contract\Mappable;
use Stringable;

final readonly class QueryParams implements Mappable, Stringable
{
    /** @var QueryParam[] */
    private array $params;

    public function __construct(
        QueryParam ...$params
    ) {
        $this->params = $params;
    }

    public function __toString(): string
    {
        return implode('&', array_map('strval', $this->params));
    }

    /**
     * @return array<string, string|string[]>
     */
    public function toArray(): array
    {
        $paramsArray = [];

        foreach ($this->params as $param) {
            $paramsArray[$param->field()] = $param->value();
        }

        return $paramsArray;
    }
}
