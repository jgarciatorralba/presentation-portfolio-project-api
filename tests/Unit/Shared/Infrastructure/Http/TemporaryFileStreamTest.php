<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Infrastructure\Http;

use App\Shared\Infrastructure\Http\TemporaryFileStream;
use App\Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

final class TemporaryFileStreamTest extends TestCase
{
    #[DataProvider('dataCreateStream')]
    public function testItCreatesAStream(
        string $content
    ): void {
        $stream = new TemporaryFileStream($content);

        $this->assertEquals(0, $stream->tell());
        $this->assertEquals($content, (string) $stream);
    }

    /**
     * @return array<string, array<string>>
     */
    public static function dataCreateStream(): array
    {
        return [
            'with content' => [FakeValueGenerator::text()],
            'with no content' => [''],
        ];
    }

    public function testItDetachesTheResource(): void
    {
        $content = FakeValueGenerator::text();
        $stream = new TemporaryFileStream($content);

        $resource = $stream->detach();

        $this->assertIsResource($resource);
        $this->assertEmpty((string) $stream);
    }

    public function testItClosesTheStream(): void
    {
        $stream = new TemporaryFileStream();

        $stream->close();

        $this->expectException(\TypeError::class);

        $stream->getSize();
    }

    public function testItGetsTheSizeOfTheStream(): void
    {
        $content = FakeValueGenerator::text();
        $stream = new TemporaryFileStream($content);

        $this->assertEquals(strlen($content), $stream->getSize());
    }

    public function testItTellsThePositionOfTheStream(): void
    {
        $content = FakeValueGenerator::text();
        $stream = new TemporaryFileStream($content);

        $charsToRead = FakeValueGenerator::integer(
            max: strlen($content)
        );
        $stream->read($charsToRead);

        $this->assertEquals($charsToRead, $stream->tell());
    }
}
