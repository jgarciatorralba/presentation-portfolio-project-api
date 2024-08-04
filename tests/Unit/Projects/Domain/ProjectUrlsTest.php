<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Domain;

use App\Projects\Domain\ProjectUrls;
use App\Tests\Unit\Projects\Domain\Factory\ProjectUrlsFactory;
use PHPUnit\Framework\TestCase;

class ProjectUrlsTest extends TestCase
{
    public function testProjectUrlsIsCreated(): void
    {
        $projectUrlsCreated = ProjectUrlsFactory::create();

        $projectUrlsAsserted = ProjectUrls::create(
            $projectUrlsCreated->repository(),
            $projectUrlsCreated->homepage()
        );

        $this->assertEquals($projectUrlsCreated, $projectUrlsAsserted);
    }
}
