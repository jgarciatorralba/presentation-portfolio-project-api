<?php

declare(strict_types=1);

namespace App\Projects\Infrastructure\Http;

use App\Projects\Domain\Project;
use App\Shared\Domain\Contract\Http\HttpClient;
use App\Shared\Domain\Contract\Logger;
use App\Shared\Domain\ValueObject\Url;

abstract class BaseProjectRetriever
{
    protected readonly Url $baseUri;

    /** @throws \InvalidArgumentException */
    public function __construct(
        protected readonly string $apiToken,
        string $baseUri,
        protected readonly HttpClient $httpClient,
        protected readonly Logger $logger
    ) {
        $this->baseUri = Url::fromString($baseUri);
    }

    /** @param array<string, mixed> $data */
    abstract protected function createProjectFromData(array $data): Project;
}
