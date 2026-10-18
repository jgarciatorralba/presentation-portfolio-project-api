<?php

declare(strict_types=1);

namespace Tests\Unit\Shared\Infrastructure\Testing;

use App\Shared\Domain\Bus\Command\Command;
use App\Shared\Domain\Bus\Event\Event;
use App\Shared\Domain\Bus\Query\Query;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class SymfonyMessageBusMock extends AbstractMock
{
    private static int $callIndex;

    public function __construct(TestCase $testCase)
    {
        parent::__construct($testCase);
        self::$callIndex = 0;
    }

    protected function getClassName(): string
    {
        return MessageBusInterface::class;
    }

    #[\Override]
    public function getMock(): MockObject&MessageBusInterface
    {
        /** @var MockObject&MessageBusInterface $mock */
        $mock = parent::getMock();

        return $mock;
    }

    /**
     * @param list<array{
     *      event: Event,
     *      exception: \Throwable|null
     * }> $events
     */
    public function shouldDispatchEventsOrThrowExceptions(array $events): void
    {
        $this->mock
            ->expects($this->exactly(count($events)))
            ->method('dispatch')
            ->with(
                $this->testCase->callback(
                    function (Event $event) use ($events): bool {
                        if (!isset($events[self::$callIndex])) {
                            return false;
                        }

                        return $event->eventId() === ($events[self::$callIndex]['event'])->eventId();
                    }
                )
            )
            ->willReturnCallback(
                function (Event $event) use ($events): Envelope {
                    if ($events[self::$callIndex++]['exception'] === null) {
                        return new Envelope($event);
                    }

                    throw $events[self::$callIndex - 1]['exception'];
                }
            );
    }

    public function shouldDispatchCommand(Command $command): void
    {
        $this->mock
            ->expects($this->once())
            ->method('dispatch')
            ->with($command);
    }

    public function shouldDispatchQuery(Query $query, HandledStamp $stamp): void
    {
        $envelope = new Envelope($query, [$stamp]);

        $this->mock
            ->expects($this->once())
            ->method('dispatch')
            ->with($query)
            ->willReturn($envelope);
    }

    public function willThrowException(\Throwable $exception): void
    {
        $this->mock
            ->expects($this->once())
            ->method('dispatch')
            ->willThrowException($exception);
    }
}
