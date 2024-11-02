<?php

declare(strict_types=1);

namespace App\Tests\Unit\UI\Exception;

use App\Shared\Domain\Http\HttpStatusCode;
use App\UI\Exception\ValidationException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ValidationExceptionTest extends TestCase
{
    #[DataProvider('dataIsCreated')]
    /**
     * @param array<string, string> $errors
     */
    public function testExceptionIsCreated(array $errors): void
    {
        $exception = new ValidationException($errors);

        $this->assertEquals('Invalid request data.', $exception->getMessage());
        $this->assertEquals(HttpStatusCode::HTTP_BAD_REQUEST->value, $exception->getStatusCode());
        $this->assertEquals($errors, $exception->getErrors());
    }

    /**
     * @return array<string, array<array<string, string>>>
     */
    public static function dataIsCreated(): array
    {
        return [
            'errors' => [
                [
                    'foo' => 'bar',
                    'baz' => 'qux',
                ]
            ],
            'no errors' => [
                []
            ],
        ];
    }
}
