<?php

declare(strict_types=1);

namespace App\Tests\Unit\UI\TestCase;

use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class ParameterBagMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return ParameterBagInterface::class;
    }

    public function shouldGetBaseUrl(string $baseUrl): self
    {
        $this->mock
            ->expects($this->once())
            ->method('get')
            ->with('base_url')
            ->willReturn($baseUrl);

        return $this;
    }
}
