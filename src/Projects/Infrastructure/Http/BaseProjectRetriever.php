<?php

declare(strict_types=1);

namespace App\Projects\Infrastructure\Http;

use App\Projects\Domain\Project;
use App\Shared\Domain\Contract\HttpClient;
use App\Shared\Domain\Contract\Logger;

abstract class BaseProjectRetriever
{
    public function __construct(
        protected readonly string $apiToken,
        protected readonly string $baseUri,
        protected readonly HttpClient $httpClient,
        protected readonly Logger $logger
    ) {
    }

    /** @param array<string, mixed> $data */
    abstract protected function createProjectFromData(array $data): Project;
}
