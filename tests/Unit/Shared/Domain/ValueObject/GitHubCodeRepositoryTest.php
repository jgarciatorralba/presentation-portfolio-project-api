<?php

declare(strict_types=1);

namespace Tests\Unit\Shared\Domain\ValueObject;

use App\Shared\Domain\Exception\InvalidCodeRepositoryUrlException;
use App\Shared\Domain\ValueObject\GitHubCodeRepository;
use Tests\Support\Builder\Projects\Domain\ProjectBuilder;
use Tests\Support\Builder\Shared\Domain\ValueObject\GitHubCodeRepositoryBuilder;
use PHPUnit\Framework\TestCase;

final class GitHubCodeRepositoryTest extends TestCase
{
    private ?GitHubCodeRepository $expected;

    protected function setUp(): void
    {
        $this->expected = GitHubCodeRepositoryBuilder::any()->build();
    }

    protected function tearDown(): void
    {
        $this->expected = null;
    }

    public function testItIsCreatedFromUrlValue(): void
    {
        $actual = GitHubCodeRepository::fromUrlValue(
            url: $this->expected->urlValue()
        );

        $this->assertEquals($this->expected, $actual);
    }

    public function testItThrowsExceptionWhenUrlIsNotValid(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        GitHubCodeRepository::fromUrlValue(url: 'invalid-url');
    }

    public function testItThrowsExceptionWhenUrlIsNotFromGithub(): void
    {
        $this->expectException(InvalidCodeRepositoryUrlException::class);

        GitHubCodeRepository::fromUrlValue(url: 'https://gitlab.com');
    }

    public function testItIsComparable(): void
    {
        $actual = GitHubCodeRepository::fromUrlValue(
            url: $this->expected->urlValue()
        );

        $this->assertTrue($this->expected->equals($actual));
    }

    public function testItIsComparableToDifferentClass(): void
    {
        $comparableInstance = ProjectBuilder::any()->build();

        $this->assertFalse($this->expected->equals($comparableInstance));
    }
}
