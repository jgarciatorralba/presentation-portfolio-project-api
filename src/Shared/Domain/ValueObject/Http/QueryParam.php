<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject\Http;

use Stringable;

final readonly class QueryParam implements Stringable
{
    /**
     * @param string|string[] $value
     */
    public function __construct(
        private string $field,
        private string|array $value
    ) {
    }

    public function field(): string
    {
        return $this->field;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        if (is_array($this->value)) {
            $string = '';

            foreach ($this->value as $key => $value) {
                if ($key > 0) {
                    $string .= '&';
                }
                $string .= urlencode($this->field) . '[]=' . urlencode($value);
            }

            return $string;
        }

        return urlencode($this->field) . '=' . urlencode($this->value);
    }
}
