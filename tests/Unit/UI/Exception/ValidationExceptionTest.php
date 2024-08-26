<?php

declare(strict_types=1);

namespace App\Tests\Unit\UI\Exception;

use App\UI\Exception\ValidationException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class ValidationExceptionTest extends TestCase
{
    #[DataProvider('dataIsCreated')]
    /**
     * @param array<string, string> $errors
     */
    public function testExceptionIsCreated(ValidationException $exception, array $errors): void
    {
        $this->assertEquals('Invalid request data.', $exception->getMessage());
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $exception->getStatusCode());
        $this->assertEquals($errors, $exception->getErrors());
    }

    /**
     * @return array<string, array<ValidationException, array<string, string>>>
     */
    public static function dataIsCreated(): array
    {
        return [
            'errors' => [
                new ValidationException($errors),
                [
                    'foo' => 'bar',
                    'baz' => 'qux',
                ]
            ],
            'no errors' => [
                new ValidationException(),
                []
            ],
        ];
    }
}
