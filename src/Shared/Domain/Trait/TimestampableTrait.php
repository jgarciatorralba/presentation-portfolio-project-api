<?php

declare(strict_types=1);

namespace App\Shared\Domain\Trait;

trait TimestampableTrait
{
    private \DateTimeImmutable $createdAt;
    private int $createdAtTimestamp;
    private \DateTimeImmutable $updatedAt;
    private ?\DateTimeImmutable $deletedAt = null;

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function createdAtTimestamp(): int
    {
        return $this->createdAtTimestamp;
    }

    public function updateCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
        $this->createdAtTimestamp = $createdAt->getTimestamp();
    }

    public function updatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function updateUpdatedAt(\DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function deletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function updateDeletedAt(?\DateTimeImmutable $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }
}
