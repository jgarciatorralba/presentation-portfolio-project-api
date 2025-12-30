<?php

declare(strict_types=1);

namespace Tests\Unit\UI\Request\Projects;

use Tests\Unit\UI\TestCase\ValidatorMock;
use App\UI\Exception\ValidationException;
use App\UI\Request\Projects\GetProjectsRequest;
use App\UI\Validation\Validator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class GetProjectsRequestTest extends TestCase
{
    private ?RequestStack $requestStack;

    protected function setUp(): void
    {
        $this->requestStack = new RequestStack();
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, string> $errors
     */
    #[DataProvider('dataGetProjectsRequest')]
    public function testItIsCreated(array $data, array $errors): void
    {
		$validatorMock = new ValidatorMock($this);
        $validatorMock->shouldValidate(
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
            validator: $validatorMock->getMock(),
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
                ['pageSize' => 3, 'maxPushedAt' => '2021-01-01'],
                []
            ],
            'invalid payload data' => [
                ['foo' => 'bar'],
                ['baz' => 'qux']
            ]
        ];
    }

	public function testGetQueryParamReturnsNullWithNonScalarInputValue(): void
	{
		$this->requestStack->push(new Request(
			query: ['pageSize' => ['invalid', 'array']]
		));

		$getProjectsRequest = new GetProjectsRequest(
			validator: $this->createStub(Validator::class),
			request: $this->requestStack
		);

		$this->assertNull($getProjectsRequest->getQueryParam('pageSize'));
	}
}
