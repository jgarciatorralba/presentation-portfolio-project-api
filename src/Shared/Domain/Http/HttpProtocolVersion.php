<?php

declare(strict_types=1);

namespace App\Shared\Domain\Http;

use App\Shared\Domain\Trait\EnumValuesProvider;

enum HttpProtocolVersion: string
{
    use EnumValuesProvider;

    case HTTP_0_9 = '0.9';
    case HTTP_1_0 = '1.0';
    case HTTP_1_1 = '1.1';
    case HTTP_2_0 = '2.0';
    case HTTP_3_0 = '3.0';

    public static function fromServerEnvironment(): self
    {
        $serverProtocol = $_SERVER['SERVER_PROTOCOL'] ?? '';

        return match ($serverProtocol) {
            'HTTP/0.9' => self::HTTP_0_9,
            'HTTP/1.0' => self::HTTP_1_0,
            'HTTP/1.1' => self::HTTP_1_1,
            'HTTP/2.0' => self::HTTP_2_0,
            'HTTP/3.0' => self::HTTP_3_0,
            default => self::default(),
        };
    }

    private static function default(): self
    {
        return self::HTTP_1_1;
    }
}
