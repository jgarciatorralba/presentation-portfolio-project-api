<?php

declare(strict_types=1);

namespace App\Tests\Builder\Projects\Domain\ValueObject;

use App\Projects\Domain\ValueObject\ProjectDetails;
use App\Tests\Builder\BuilderInterface;
use App\Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;

final class ProjectDetailsBuilder implements BuilderInterface
{
    private const int MIN_TOPICS = 1;
    private const int MAX_TOPICS = 20;

    /**
     * @param list<string>|null $topics
     */
    private function __construct(
        private string $name,
        private ?string $description,
        private ?array $topics,
    ) {
    }

    public static function any(): self
    {
        return new self(
            name: FakeValueGenerator::string(),
            description: FakeValueGenerator::randomElement([
                null,
                FakeValueGenerator::text()
            ]),
            topics: self::generateRandomTopics()
        );
    }

    public function withName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function withDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param list<string>|null $topics
     */
    public function withTopics(?array $topics): self
    {
        $this->topics = $topics;

        return $this;
    }

    public function build(): ProjectDetails
    {
        return ProjectDetails::create(
            name: $this->name,
            description: $this->description,
            topics: $this->topics,
        );
    }

    /** @return list<string> */
    private static function generateRandomTopics(?int $numTopics = null): array
    {
        if ($numTopics === null) {
            $numTopics = FakeValueGenerator::integer(
                self::MIN_TOPICS,
                self::MAX_TOPICS
            );
        }

        $topics = [];
        for ($i = 0; $i < $numTopics; $i++) {
            $topics[] = FakeValueGenerator::string();
        }

        return $topics;
    }
}
