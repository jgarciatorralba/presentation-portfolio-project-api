<?php

declare(strict_types=1);

namespace App\Projects\Infrastructure\Persistence\Doctrine\Type;

use App\Projects\Domain\Exception\InvalidProjectRepositoryException;
use App\Projects\Domain\ValueObject\ProjectRepository;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

class ProjectRepositoryType extends Type
{
    public function getName(): string
    {
        return 'project_repository';
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $this->getName();
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): mixed
    {
        if ($value instanceof ProjectRepository) {
            return $value;
        }

        if (empty($value) || !is_string($value)) {
            throw new ConversionException(
                sprintf(
                    "Invalid %s value: %s. Must be a string.",
                    $this->getName(),
                    is_object($value) ? get_class($value) : gettype($value)
                )
            );
        }

        try {
            return ProjectRepository::fromString($value);
        } catch (\InvalidArgumentException | InvalidProjectRepositoryException $e) {
            throw new ConversionException(
                sprintf(
                    "Conversion failed: %s",
                    $e->getMessage()
                )
            );
        }
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): mixed
    {
        if ($value instanceof ProjectRepository) {
            return $value->__toString();
        }

        if (empty($value) || !is_string($value)) {
            throw new ConversionException(
                sprintf(
                    "Invalid %s value: %s. Must be string or an instance of %s class.",
                    $this->getName(),
                    is_object($value) ? get_class($value) : gettype($value),
                    ProjectRepository::class
                )
            );
        }

        return $value;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
