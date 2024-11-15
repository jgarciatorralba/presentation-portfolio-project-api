<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Query\InMemory;

use App\Shared\Application\Bus\Exception\QueryNotRegisteredException;
use App\Shared\Domain\Bus\Query\Query;
use App\Shared\Domain\Bus\Query\QueryBus;
use App\Shared\Domain\Bus\Query\Response;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;

final readonly class InMemorySymfonyQueryBus implements QueryBus
{
    public function __construct(
        private MessageBusInterface $queryBus
    ) {
    }

    /**
     * @throws QueryNotRegisteredException
     * @throws ExceptionInterface
     */
    public function ask(Query $query): ?Response
    {
        try {
            /** @var HandledStamp $stamp */
            $stamp = $this->queryBus->dispatch($query)->last(HandledStamp::class);

            return $stamp->getResult();
        } catch (NoHandlerForMessageException) {
            throw new QueryNotRegisteredException($query);
        } catch (HandlerFailedException $exception) {
            throw $exception->getPrevious() ?? $exception;
        }
    }
}
