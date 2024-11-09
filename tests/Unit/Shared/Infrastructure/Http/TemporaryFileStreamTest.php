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
        $result = $stream->read($charsToRead);

        $this->assertEquals(substr($content, 0, $charsToRead), $result);
    }

    public function testItChecksIfTheStreamIsAtTheEnd(): void
    {
        $content = FakeValueGenerator::text();
        $stream = new TemporaryFileStream($content);

        $stream->getContents();

        $this->assertTrue($stream->eof());
    }

    public function testItChecksIfTheStreamIsSeekable(): void
    {
        $stream = new TemporaryFileStream();

        $this->assertTrue($stream->isSeekable());
    }

    public function testItSeeksToAPositionInTheStream(): void
    {
        $content = FakeValueGenerator::text();
        $stream = new TemporaryFileStream($content);

        $position = FakeValueGenerator::integer(
            max: strlen($content)
        );
        $result = $stream->seek($position);

        $this->assertEquals($position, $stream->tell());
        $this->assertEmpty($result);
    }

    public function testItRewindsTheStream(): void
    {
        $content = FakeValueGenerator::text();
        $stream = new TemporaryFileStream($content);

        $position = FakeValueGenerator::integer(
            max: strlen($content)
        );

        $stream->read($position);
        $stream->rewind();

        $this->assertEquals(0, $stream->tell());
    }

    public function testItChecksIfTheStreamIsWritable(): void
    {
        $stream = new TemporaryFileStream();

        $this->assertTrue($stream->isWritable());
    }

    public function testItWritesToTheStream(): void
    {
        $stream = new TemporaryFileStream();
        $content = FakeValueGenerator::text();

        $stream->write($content);

        $this->assertEquals($content, (string) $stream);
    }

    public function testItChecksIfTheStreamIsReadable(): void
    {
        $stream = new TemporaryFileStream();

        $this->assertTrue($stream->isReadable());
    }

    public function testItReadsFromTheStream(): void
    {
        $content = FakeValueGenerator::text();
        $stream = new TemporaryFileStream($content);

        $charsToRead = FakeValueGenerator::integer(
            max: strlen($content)
        );
        $result = $stream->read($charsToRead);

        $this->assertEquals(substr($content, 0, $charsToRead), $result);
    }

    public function testItGetsTheMetadataOfTheStream(): void
    {
        $stream = new TemporaryFileStream();

        $metadata = $stream->getMetadata();

        $this->assertIsArray($metadata);

        $keys = ['wrapper_type', 'stream_type', 'mode', 'unread_bytes', 'uri', 'seekable'];
        foreach ($keys as $key) {
            $this->assertArrayHasKey($key, $metadata);
        }
    }

    public function testItGetsTheMetadataOfTheStreamByKey(): void
    {
        $stream = new TemporaryFileStream();

        $keys = ['wrapper_type', 'stream_type', 'mode', 'unread_bytes', 'uri', 'seekable'];
        foreach ($keys as $key) {
            $metadata = $stream->getMetadata($key);

            $this->assertNotNull($metadata);
        }

        $this->assertNull($stream->getMetadata('non_existent_key'));
    }

    public function testItGetsTheContentsOfTheStream(): void
    {
        $content = FakeValueGenerator::text();
        $stream = new TemporaryFileStream($content);

        $result = $stream->getContents();

        $this->assertEquals($content, $result);
    }
}
