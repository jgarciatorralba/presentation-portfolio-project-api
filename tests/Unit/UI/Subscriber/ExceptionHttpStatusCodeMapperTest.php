<?php

declare(strict_types=1);

namespace App\Tests\Unit\UI\Subscriber;

use App\UI\Subscriber\ExceptionHttpStatusCodeMapper;
use App\Projects\Domain\Exception\ProjectAlreadyExistsException;
use App\Projects\Domain\Exception\ProjectNotFoundException;
use DateMalformedStringException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ExceptionHttpStatusCodeMapperTest extends TestCase
{
    private ?ExceptionHttpStatusCodeMapper $sut;

    protected function setUp(): void
    {
        $this->sut = new ExceptionHttpStatusCodeMapper();
    }

    protected function tearDown(): void
    {
        $this->sut = null;
    }

    #[DataProvider('exceptions')]
    public function testItMapsStatusCode(string $exceptionClassName, ?int $statusCode): void
    {
        $this->assertEquals(
            $statusCode,
            $this->sut->getStatusCodeFor($exceptionClassName)
        );
    }

    /**
     * @return array<string, array<class-string, int|null>>
     */
    public static function exceptions(): array
    {
        return [
            'ProjectNotFoundException' => [ProjectNotFoundException::class, 404],
            'ProjectAlreadyExistsException' => [ProjectAlreadyExistsException::class, 409],
            'DateMalformedStringException' => [DateMalformedStringException::class, null],
        ];
    }
}
