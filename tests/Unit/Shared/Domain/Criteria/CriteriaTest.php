<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\Criteria;

use App\Shared\Domain\Criteria\Criteria;
use App\Tests\Unit\Shared\Domain\Criteria\Factory\CriteriaFactory;
use PHPUnit\Framework\TestCase;

class CriteriaTest extends TestCase
{
    public function testCriteriaIsCreated(): void
    {
        $criteriaCreated = CriteriaFactory::create();

        $criteriaAsserted = new Criteria(
            filters: $criteriaCreated->filters(),
            orderBy: $criteriaCreated->orderBy(),
            limit: $criteriaCreated->limit(),
            offset: $criteriaCreated->offset()
        );

        $this->assertEquals($criteriaCreated, $criteriaAsserted);
    }
}
