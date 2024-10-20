<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject\Http;

enum HttpProtocolVersion: string
{
    case HTTP_0_9 = '0.9';
    case HTTP_1_0 = '1.0';
    case HTTP_1_1 = '1.1';
    case HTTP_2_0 = '2.0';
    case HTTP_3_0 = '3.0';

    public static function default(): self
    {
        return self::HTTP_1_1;
    }
}
