<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Doctrine\Type;

use App\Shared\Domain\ValueObject\Url;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

class UrlType extends Type
{
    public function getName(): string
    {
        return 'url';
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $this->getName();
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): mixed
    {
        if ($value instanceof Url || $value === null) {
            return $value;
        }

        if (!is_string($value)) {
            throw new ConversionException(
                sprintf(
                    "Invalid %s value: %s. Must be null or a string.",
                    $this->getName(),
                    is_object($value) ? get_class($value) : gettype($value)
                )
            );
        }

        try {
            return Url::fromString($value);
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
        if ($value instanceof Url) {
            return $value->__toString();
        }

        if (null === $value || $value === '') {
            return null;
        }

        if (!is_string($value)) {
            throw new ConversionException(
                sprintf(
                    "Invalid %s value: %s. Must be string, null or an instance of %s class.",
                    $this->getName(),
                    is_object($value) ? get_class($value) : gettype($value),
                    Url::class
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
