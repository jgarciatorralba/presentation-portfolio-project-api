<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\TestCase;

use App\Shared\Domain\Bus\Event\Event;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\InvalidArgumentException;
use PHPUnit\Framework\MockObject\MethodCannotBeConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameAlreadyConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameNotConfiguredException;
use PHPUnit\Framework\MockObject\MethodParametersAlreadyConfiguredException;
use Symfony\Component\Messenger\MessageBusInterface;

final class EventBusMock extends AbstractMock
{
    private static int $callIndex;

    public function __construct()
    {
        parent::__construct();
        self::$callIndex = 0;
    }

    protected function getClassName(): string
    {
        return MessageBusInterface::class;
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     * @throws MethodNameNotConfiguredException
     * @throws MethodParametersAlreadyConfiguredException
     *
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
                $this->callback(
                    function (Event $event) use ($events): bool {
                        if ($events[self::$callIndex]['exception'] !== null) {
                            throw $events[self::$callIndex++]['exception'];
                        }
                        return $event === $events[self::$callIndex++]['event'];
                    }
                )
            );
    }
}
