<?php

declare(strict_types=1);

namespace App\Projects\Domain\ValueObject;

use App\Shared\Domain\Contract\Comparable;

final readonly class ProjectDetails implements Comparable
{
    /** @param list<string>|null $topics */
    private function __construct(
        private string $name,
        private ?string $description,
        private ?array $topics,
    ) {
    }

    /** @param list<string>|null $topics */
    public static function create(
        string $name,
        ?string $description,
        ?array $topics,
    ): self {
        return new self(
            name: $name,
            description: $description,
            topics: $topics,
        );
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    /** @return list<string>|null */
    public function topics(): ?array
    {
        return $this->topics;
    }

    public function equals(Comparable $details): bool
    {
        if (!$details instanceof self) {
            return false;
        }

        if ($this->topics === null xor $details->topics === null) {
            return false;
        }

        if ($this->topics !== null && $details->topics !== null) {
            if (count($this->topics) !== count($details->topics)) {
                return false;
            }
            foreach ($this->topics as $topic) {
                if (!in_array($topic, $details->topics)) {
                    return false;
                }
            }
        }

        return $this->name === $details->name
            && $this->description === $details->description;
    }
}
