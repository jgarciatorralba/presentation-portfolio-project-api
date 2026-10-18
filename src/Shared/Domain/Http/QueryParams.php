<?php

declare(strict_types=1);

namespace App\Shared\Domain\Http;

use App\Shared\Domain\Contract\ArraySerializable;
use Stringable;

final readonly class QueryParams implements ArraySerializable, Stringable
{
    /** @var QueryParam[] */
    private array $params;

    public function __construct(
        QueryParam ...$params
    ) {
        $mappedParams = [];
        foreach ($params as $param) {
            $mappedParams[$param->field()] = $param;
        }

        $this->params = array_values($mappedParams);
    }

    #[\Override]
    public function __toString(): string
    {
        return implode('&', array_map(strval(...), $this->params));
    }

    /**
     * @return array<string, string|string[]>
     */
    #[\Override]
    public function toArray(): array
    {
        $paramsArray = [];

        foreach ($this->params as $param) {
            $paramsArray[$param->field()] = $param->value();
        }

        return $paramsArray;
    }
}
