<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Application\Command\CreateProject;

use App\Projects\Application\Command\CreateProject\CreateProjectCommandHandler;
use App\Tests\Unit\Projects\Application\Command\CreateProject\Factory\CreateProjectCommandFactory;
use App\Tests\Unit\Projects\Domain\Factory\ProjectFactory;
use App\Tests\Unit\Projects\TestCase\CreateProjectMock;
use App\Tests\Unit\Shared\TestCase\LocalDateTimeZoneConverterMock;
use PHPUnit\Framework\TestCase;

final class CreateProjectCommandHandlerTest extends TestCase
{
    private ?CreateProjectMock $createProject;
    private ?LocalDateTimeZoneConverterMock $dateTimeConverter;
    private ?CreateProjectCommandHandler $sut;

    protected function setUp(): void
    {
        $this->createProject = new CreateProjectMock($this);
        $this->dateTimeConverter = new LocalDateTimeZoneConverterMock($this);
        $this->sut = new CreateProjectCommandHandler(
            createProject: $this->createProject->getMock(),
            dateTimeConverter: $this->dateTimeConverter->getMock()
        );
    }

    protected function tearDown(): void
    {
        $this->createProject = null;
        $this->dateTimeConverter = null;
        $this->sut = null;
    }

    public function testCreateProject(): void
    {
        $now = new \DateTimeImmutable();
        $project = ProjectFactory::create(
            createdAt: $now,
            updatedAt: $now,
        );
        $command = CreateProjectCommandFactory::createFromProject($project);

        $this->dateTimeConverter->shouldConvert(
            $project->lastPushedAt(),
            (clone($project->lastPushedAt()))
                ->setTimezone(new \DateTimeZone(date_default_timezone_get()))
        );

        $this->createProject->shouldCreateProject($project);

        $result = $this->sut->__invoke(
            command: $command
        );
        $this->assertNull($result);
    }
}
