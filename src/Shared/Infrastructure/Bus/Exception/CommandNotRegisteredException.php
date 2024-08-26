<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Exception;

use App\Shared\Domain\Bus\Command\Command;
use App\Shared\Utils;

class CommandNotRegisteredException extends \Exception
{
    public function __construct(Command $command)
    {
        $message = sprintf(
            'Command with class %s has no handler registered',
            Utils::extractClassName($command::class)
        );

        parent::__construct($message);
    }
}
