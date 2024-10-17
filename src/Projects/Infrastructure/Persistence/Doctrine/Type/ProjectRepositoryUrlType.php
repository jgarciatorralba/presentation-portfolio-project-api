<?php

declare(strict_types=1);

namespace App\Projects\Infrastructure\Persistence\Doctrine\Type;

use App\Projects\Domain\Exception\InvalidProjectRepositoryUrlException;
use App\Projects\Domain\ValueObject\ProjectRepositoryUrl;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

class ProjectRepositoryUrlType extends Type
{
    public function getName(): string
    {
        return 'project_repository_url';
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $this->getName();
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): mixed
    {
        if ($value instanceof ProjectRepositoryUrl) {
            return $value;
        }

        if (empty($value) || !is_string($value)) {
            throw new ConversionException(
                sprintf(
                    "Invalid %s value: %s. Must be a string.",
                    $this->getName(),
                    get_debug_type($value)
                )
            );
        }

        try {
            return ProjectRepositoryUrl::fromString($value);
        } catch (\InvalidArgumentException | InvalidProjectRepositoryUrlException $e) {
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
        if ($value instanceof ProjectRepositoryUrl) {
            return $value->__toString();
        }

        if (empty($value) || !is_string($value)) {
            throw new ConversionException(
                sprintf(
                    "Invalid %s value: %s. Must be string or an instance of %s class.",
                    $this->getName(),
                    get_debug_type($value),
                    ProjectRepositoryUrl::class
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
