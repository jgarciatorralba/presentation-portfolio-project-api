<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Domain;

use App\Projects\Domain\ProjectDetails;
use App\Tests\Unit\Projects\Domain\Factory\ProjectDetailsFactory;
use PHPUnit\Framework\TestCase;

class ProjectDetailsTest extends TestCase
{
    public function testProjectDetailsAreCreated(): void
    {
        $projectDetailsCreated = ProjectDetailsFactory::create();

        $projectDetailsAsserted = ProjectDetails::create(
            $projectDetailsCreated->name(),
            $projectDetailsCreated->description(),
            $projectDetailsCreated->topics()
        );

        $this->assertEquals($projectDetailsCreated, $projectDetailsAsserted);
    }
}
