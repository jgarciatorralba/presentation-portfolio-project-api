<?php

namespace App\UI\Command\Projects;

use App\Projects\Application\Bus\Event\SyncProjectsRequestedEvent;
use App\Shared\Domain\Bus\Event\EventBus;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:projects:sync',
    description: 'Publishes an event to synchronize the internal projects database with an external API',
)]
class SyncProjectsCommand extends Command
{
    use LockableTrait;

    public function __construct(
        private readonly EventBus $eventBus
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if (!$this->lock()) {
            $io->warning('The command is already running in another process.');
            return Command::SUCCESS;
        }

        $event = new SyncProjectsRequestedEvent();
        $this->eventBus->publish($event);

        $io->success("Event {$event->eventId()} published successfully on {$event->occurredOn()}.");
        return Command::SUCCESS;
    }
}
