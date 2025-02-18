<?php

declare(strict_types=1);

namespace App\Shared\Application\Bus\Event;

use App\Shared\Domain\Bus\Event\Event;

abstract readonly class ApplicationEvent extends Event
{
}
