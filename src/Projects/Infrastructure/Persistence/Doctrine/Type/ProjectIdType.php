<?php

declare(strict_types=1);

namespace App\Projects\Infrastructure\Persistence\Doctrine\Type;

use App\Projects\Domain\ValueObject\ProjectId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

class ProjectIdType extends Type
{
    public function getName(): string
    {
        return 'project_id';
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $this->getName();
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): mixed
    {
        if ($value instanceof ProjectId) {
            return $value;
        }

        if (empty($value) || !is_int($value)) {
            throw new ConversionException(
                sprintf(
                    "Invalid %s value: %s. Must be a positive integer.",
                    $this->getName(),
                    get_debug_type($value)
                )
            );
        }

        try {
            return ProjectId::create($value);
        } catch (\InvalidArgumentException $e) {
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
        if ($value instanceof ProjectId) {
            return $value->value();
        }

        if (empty($value) || !is_int($value)) {
            throw new ConversionException(
                sprintf(
                    "Invalid %s value: %s. Must be a positive integer or an instance of %s class.",
                    $this->getName(),
                    get_debug_type($value),
                    ProjectId::class
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
