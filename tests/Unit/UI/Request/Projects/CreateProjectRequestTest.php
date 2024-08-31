<?php

declare(strict_types=1);

namespace App\Tests\Unit\UI\Request\Projects;

use App\Shared\Domain\Service\LocalDateTimeZoneConverter;
use App\Tests\Unit\UI\TestCase\ValidatorMock;
use App\UI\Exception\ValidationException;
use App\UI\Request\Projects\CreateProjectRequest;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class CreateProjectRequestTest extends TestCase
{
    private ?ValidatorMock $validatorMock;
    private ?RequestStack $requestStack;

    protected function setUp(): void
    {
        $this->validatorMock = new ValidatorMock();
        $this->requestStack = new RequestStack();
    }

    protected function tearDown(): void
    {
        $this->validatorMock = null;
        $this->requestStack = null;
    }

    #[DataProvider('dataCreateProjectRequest')]
    /**
     * @param array<string, mixed> $data
     * @param array<string, string> $errors
     */
    public function testItCreatesRequest(array $data, array $errors): void
    {
        $this->validatorMock->shouldValidate(
            data: $data,
            errors: $errors
        );

        if (!empty($errors)) {
            $this->expectException(ValidationException::class);
            $this->expectExceptionMessage('Invalid request data.');
        }

        $this->requestStack->push(new Request(
            content: json_encode($data)
        ));

        $createProjectRequest = new CreateProjectRequest(
            validator: $this->validatorMock->getMock(),
            request: $this->requestStack,
            dateTimeConverter: $this->createMock(LocalDateTimeZoneConverter::class)
        );

        $this->assertEquals(
            $data,
            $createProjectRequest->payload()
        );
    }

    /**
     * @return array<string, array<array<string, mixed>>>
     */
    public static function dataCreateProjectRequest(): array
    {
        return [
            'empty payload' => [
                [],
                ['name' => 'This value should not be blank.']
            ],
            'valid payload data' => [
                [
                    'name' => 'foo',
                    'description' => 'bar',
                    'topics' => ['baz', 'qux'],
                    'repository' => 'https://www.github.com/foo/bar',
                    'homepage' => 'https://www.foo.com',
                    'archived' => false,
                    'lastPushedAt' => '2021-01-01T00:00:00+00:00'
                ],
                []
            ],
            'invalid payload data' => [
                ['foo' => 'bar'],
                ['baz' => 'qux']
            ]
        ];
    }
}
