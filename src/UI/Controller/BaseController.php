<?php

declare(strict_types=1);

namespace App\UI\Controller;

use App\Shared\Domain\Bus\Query\Query;
use App\Shared\Domain\Bus\Query\QueryBus;
use App\Shared\Domain\Bus\Query\Response;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;

abstract class BaseController
{
    private readonly string $baseUrl;

    /**
     * @throws ParameterNotFoundException
     * @throws \TypeError
     */
    public function __construct(
        private readonly QueryBus $queryBus,
        ParameterBagInterface $params
    ) {
        $this->baseUrl = $params->get('base_url');
    }

    protected function ask(Query $query): ?Response
    {
        return $this->queryBus->ask($query);
    }

    protected function getResourceUrl(string $resourceName, int $id): string
    {
        return sprintf('%s/api/%s/%d', $this->baseUrl, $resourceName, $id);
    }
}
