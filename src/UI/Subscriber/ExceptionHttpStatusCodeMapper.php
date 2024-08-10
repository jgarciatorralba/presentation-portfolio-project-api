<?php

declare(strict_types=1);

namespace App\UI\Subscriber;

use App\Projects\Domain\Exception\ProjectAlreadyExistsException;
use App\Projects\Domain\Exception\ProjectNotFoundException;
use Symfony\Component\HttpFoundation\Response;

final class ExceptionHttpStatusCodeMapper
{
    private const EXCEPTIONS = [
        ProjectNotFoundException::class => Response::HTTP_NOT_FOUND,
        ProjectAlreadyExistsException::class => Response::HTTP_CONFLICT,
    ];

    public function getStatusCodeFor(string $exceptionClass): ?int
    {
        return self::EXCEPTIONS[$exceptionClass] ?? null;
    }
}
