<?php

declare(strict_types=1);

namespace App\Projects\Domain;

final class ProjectDetails
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

    public function updateName(string $name): void
    {
        $this->name = $name;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function updateDescription(?string $description): void
    {
        $this->description = $description;
    }

    /** @return list<string>|null */
    public function topics(): ?array
    {
        return $this->topics;
    }

    /** @param list<string>|null $topics */
    public function updateTopics(?array $topics): void
    {
        $this->topics = $topics;
    }
}
