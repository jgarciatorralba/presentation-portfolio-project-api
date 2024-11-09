<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Http;

use App\Shared\Domain\Contract\Http\DataStream;

final class TemporaryFileStream implements DataStream
{
    /** @var resource|null */
    private $resource;

    public function __construct(string $content = '')
    {
        $handle = fopen('php://temp', 'r+');

        if ($handle === false) {
            throw new \RuntimeException('Unable to open temporary stream');
        }

        $this->resource = $handle;

        $this->write($content);
        $this->rewind();
    }

    public function __toString(): string
    {
        try {
            $readString = stream_get_contents($this->resource, -1, 0);
            return $readString !== false ? $readString : '';
        } catch (\Throwable) {
            return '';
        }
    }

    public function close(): void
    {
        $result = fclose($this->resource);

        if ($result === false) {
            throw new \RuntimeException('Unable to close stream');
        }
    }

    /**
     * @return resource
     */
    public function detach()
    {
        $oldResource = $this->resource;
        $this->resource = null;
        return $oldResource;
    }

    public function getSize(): ?int
    {
        $stat = fstat($this->resource);
        return $stat['size'] ?? null;
    }

    public function tell(): int
    {
        $position = ftell($this->resource);
        if ($position === false) {
            throw new \RuntimeException('Unable to determine stream position');
        }

        return $position;
    }

    public function eof(): bool
    {
        return feof($this->resource);
    }

    public function isSeekable(): bool
    {
        return true;
    }

    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        $result = fseek($this->resource, $offset, $whence);

        if ($result === -1) {
            throw new \RuntimeException('Unable to seek to stream position ' . $offset);
        }
    }

    public function rewind(): void
    {
        $result = rewind($this->resource);

        if ($result === false) {
            throw new \RuntimeException('Unable to rewind stream');
        }
    }

    public function isWritable(): bool
    {
        return true;
    }

    public function write(string $string): int
    {
        $bytes = fwrite($this->resource, $string);

        if ($bytes === false) {
            throw new \RuntimeException('Unable to write to stream');
        }

        return $bytes;
    }

    public function isReadable(): bool
    {
        return true;
    }

    public function read(int $length): string
    {
        $string = fread($this->resource, $length);

        if ($string === false) {
            throw new \RuntimeException('Unable to read from stream');
        }

        return $string;
    }

    public function getContents(): string
    {
        $contents = stream_get_contents($this->resource);

        if ($contents === false) {
            throw new \RuntimeException('Unable to read stream contents');
        }

        return $contents;
    }

    /**
     * @return array{
     *      timed_out: bool,
     *      blocked: bool,
     *      eof: bool,
     *      unread_bytes: int,
     *      stream_type: string,
     *      wrapper_type: string,
     *      wrapper_data: mixed,
     *      mode: string,
     *      seekable: bool,
     *      uri: string,
     *      crypto?: array<mixed>,
     *      mediatype: string
     * }|mixed|null
     */
    public function getMetadata(?string $key = null): mixed
    {
        $metadata = stream_get_meta_data($this->resource);

        if ($key === null) {
            return $metadata;
        }

        return $metadata[$key] ?? null;
    }
}
