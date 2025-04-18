<?php

namespace App\UI\Command\Projects;

use App\Projects\Application\Bus\Event\SyncProjectsRequestedEvent;
use App\Shared\Domain\Bus\Event\EventBus;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Lock\Exception\LockAcquiringException;
use Symfony\Component\Lock\Exception\LockConflictedException;
use Symfony\Component\Lock\Exception\LockReleasingException;
use Symfony\Component\Lock\LockFactory;

#[AsCommand(
    name: 'app:projects:sync',
    description: 'Publishes an event to synchronize the internal projects database with an external API',
)]
class SyncProjectsCommand extends Command
{
    public function __construct(
        private readonly EventBus $eventBus,
        private readonly LockFactory $lockFactory,
    ) {
        parent::__construct();
    }

    /**
     * @throws \InvalidArgumentException
     * @throws LockAcquiringException
     * @throws LockConflictedException
     * @throws LockReleasingException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $commandStyle = new SymfonyStyle($input, $output);

        $lock = $this->lockFactory->createLock('sync_projects_command');

        if (!$lock->acquire()) {
            $commandStyle->warning('The command is already running in another process.');
            return Command::FAILURE;
        }

        try {
            $event = new SyncProjectsRequestedEvent();
            $this->eventBus->publish($event);

            $commandStyle->success("Event {$event->eventId()} published successfully on {$event->occurredOn()}");

            return Command::SUCCESS;
        } finally {
            $lock->release();
        }
    }
}
