<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\Http;

use App\Shared\Domain\Http\HttpProtocolVersion;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class HttpProtocolVersionTest extends TestCase
{
    protected function tearDown(): void
    {
        if (isset($_SERVER['SERVER_PROTOCOL'])) {
            unset($_SERVER['SERVER_PROTOCOL']);
        }

        parent::tearDown();
    }

    #[DataProvider('protocolProvider')]
    public function testItCanBeCreatedFromServerEnvironment(
        string $protocolString,
        HttpProtocolVersion $expected
    ): void {
        $_SERVER['SERVER_PROTOCOL'] = $protocolString;

        $protocol = HttpProtocolVersion::fromServerEnvironment();

        $this->assertInstanceOf(HttpProtocolVersion::class, $protocol);
        $this->assertEquals($expected, $protocol);
    }

    /**
     * @return array<array{string, HttpProtocolVersion}>
     */
    public static function protocolProvider(): array
    {
        return [
            'HTTP version 0.9' => ['HTTP/0.9', HttpProtocolVersion::HTTP_0_9],
            'HTTP version 1.0' => ['HTTP/1.0', HttpProtocolVersion::HTTP_1_0],
            'HTTP version 1.1' => ['HTTP/1.1', HttpProtocolVersion::HTTP_1_1],
            'HTTP version 2.0' => ['HTTP/2.0', HttpProtocolVersion::HTTP_2_0],
            'HTTP version 3.0' => ['HTTP/3.0', HttpProtocolVersion::HTTP_3_0],
            'Invalid version falling back to default' => ['Invalid Protocol', HttpProtocolVersion::HTTP_1_1],
        ];
    }

    public function testItHasDefaultValue(): void
    {
        $reflection = new \ReflectionClass(HttpProtocolVersion::class);
        $method = $reflection->getMethod('default');

        $default = $method->invoke(null);

        $this->assertEquals(HttpProtocolVersion::HTTP_1_1, $default);
    }
}
