<?php

declare(strict_types=1);

namespace App\UI\Subscriber;

use App\Projects\Domain\Exception\InvalidCodeRepositoryUrlException;
use App\Projects\Domain\Exception\ProjectAlreadyExistsException;
use App\Projects\Domain\Exception\ProjectNotFoundException;
use App\Shared\Domain\Http\HttpStatusCode;

final readonly class ExceptionHttpStatusCodeMapper
{
    private const array EXCEPTIONS = [
        InvalidCodeRepositoryUrlException::class => HttpStatusCode::HTTP_BAD_REQUEST->value,
        ProjectNotFoundException::class => HttpStatusCode::HTTP_NOT_FOUND->value,
        ProjectAlreadyExistsException::class => HttpStatusCode::HTTP_CONFLICT->value,
    ];

    public function getStatusCodeFor(string $exceptionClass): ?int
    {
        return self::EXCEPTIONS[$exceptionClass] ?? null;
    }
}
