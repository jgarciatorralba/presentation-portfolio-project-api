<?php

declare(strict_types=1);

namespace App\Tests\Unit\UI\Request\Projects;

use App\Tests\Unit\UI\TestCase\ValidatorMock;
use App\UI\Exception\ValidationException;
use App\UI\Request\Projects\GetProjectsRequest;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class GetProjectsRequestTest extends TestCase
{
    private ?ValidatorMock $validatorMock;
    private ?RequestStack $requestStack;

    protected function setUp(): void
    {
        $this->validatorMock = new ValidatorMock($this);
        $this->requestStack = new RequestStack();
    }

    protected function tearDown(): void
    {
        $this->validatorMock = null;
        $this->requestStack = null;
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, string> $errors
     */
    #[DataProvider('dataGetProjectsRequest')]
    public function testItIsCreated(array $data, array $errors): void
    {
        $this->validatorMock->shouldValidate(
            data: $data,
            errors: $errors
        );

        if ($errors !== []) {
            $this->expectException(ValidationException::class);
            $this->expectExceptionMessage('Invalid request data.');
        }

        $this->requestStack->push(new Request(
            content: json_encode($data)
        ));

        $getProjectsRequest = new GetProjectsRequest(
            validator: $this->validatorMock->getMock(),
            request: $this->requestStack
        );

        $this->assertEquals(
            $data,
            $getProjectsRequest->payload()
        );
    }

    /**
     * @return array<string, array<array<string, mixed>>>
     */
    public static function dataGetProjectsRequest(): array
    {
        return [
            'empty payload' => [
                [],
                []
            ],
            'valid payload data' => [
                ['pageSize' => 3, 'maxUpdatedAt' => '2021-01-01'],
                []
            ],
            'invalid payload data' => [
                ['foo' => 'bar'],
                ['baz' => 'qux']
            ]
        ];
    }
}
