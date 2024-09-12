<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Application\Command\CreateProject;

use App\Projects\Application\Command\CreateProject\CreateProjectCommandHandler;
use App\Projects\Domain\Project;
use App\Tests\Unit\Projects\Application\Command\CreateProject\Factory\CreateProjectCommandFactory;
use App\Tests\Builder\Projects\Domain\ProjectBuilder;
use App\Tests\Unit\Projects\TestCase\CreateProjectMock;
use App\Tests\Unit\Shared\TestCase\LocalDateTimeZoneConverterMock;
use PHPUnit\Framework\TestCase;

final class CreateProjectCommandHandlerTest extends TestCase
{
    private ?Project $project;
    private ?CreateProjectMock $createProject;
    private ?LocalDateTimeZoneConverterMock $dateTimeConverter;
    private ?CreateProjectCommandHandler $sut;

    protected function setUp(): void
    {
        $now = new \DateTimeImmutable();
        $this->project = ProjectBuilder::any()
            ->withCreatedAt($now)
            ->withUpdatedAt($now)
            ->build();

        $this->createProject = new CreateProjectMock($this);
        $this->dateTimeConverter = new LocalDateTimeZoneConverterMock($this);
        $this->sut = new CreateProjectCommandHandler(
            createProject: $this->createProject->getMock(),
            dateTimeConverter: $this->dateTimeConverter->getMock()
        );
    }

    protected function tearDown(): void
    {
        $this->project = null;
        $this->createProject = null;
        $this->dateTimeConverter = null;
        $this->sut = null;
    }

    public function testCreateProject(): void
    {
        $command = CreateProjectCommandFactory::createFromProject($this->project);

        $this->dateTimeConverter->shouldConvert(
            $this->project->lastPushedAt(),
            (clone($this->project->lastPushedAt()))
                ->setTimezone(new \DateTimeZone(date_default_timezone_get()))
        );

        $this->createProject->shouldCreateProject($this->project);

        $result = $this->sut->__invoke(
            command: $command
        );
        $this->assertNull($result);
    }
}
