<?php

declare(strict_types=1);

namespace Tests\Unit\UI\Subscriber;

use App\Shared\Domain\Http\HttpStatusCode;
use Tests\Unit\Shared\Domain\Testing\TestDomainException;
use Tests\Unit\UI\TestCase\ExceptionEventMock;
use Tests\Unit\UI\TestCase\ExceptionHttpStatusCodeMapperMock;
use App\UI\Exception\ValidationException;
use App\UI\Subscriber\ApiExceptionListener;
use App\UI\Subscriber\ExceptionHttpStatusCodeMapper;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

final class ApiExceptionListenerTest extends TestCase
{
    #[DataProvider('dataIsMainRequest')]
    public function testItHandlesKernelException(bool $isMainRequest): void
    {
		$exceptionEventMock = new ExceptionEventMock($this);

        $exceptionEventMock
            ->shouldGetThrowable(new \Exception('Exception message'));

        $exceptionEventMock
            ->shouldBeMainRequest($isMainRequest);

        $exceptionEventMock
            ->shouldCallSetResponse((int) $isMainRequest);

		$sut = new ApiExceptionListener(
            exceptionHttpStatusCodeMapper: $this->createStub(ExceptionHttpStatusCodeMapper::class)
        );

        $result = $sut->onKernelException($exceptionEventMock->getMock());
        $this->assertNull($result);
    }

    /**
     * return array<string, array<bool>>
     */
    public static function dataIsMainRequest(): array
    {
        return [
            'main request' => [true],
            'not main request' => [false]
        ];
    }

    /**
     * @param array{
     *      code: string,
     *      errorMessage: string
     *      errors?: array<string, string>
     * } $exceptionContent
     */
    #[DataProvider('dataBuildResponse')]
    public function testItBuildsResponse(\Throwable $exception, array $exceptionContent): void
    {
		$sut = new ApiExceptionListener(
            exceptionHttpStatusCodeMapper: $this->createStub(ExceptionHttpStatusCodeMapper::class)
        );

        $reflection = new \ReflectionClass($sut);
        $method = $reflection->getMethod('buildResponse');

        $response = $method->invoke($sut, $exception);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(
            $exceptionContent,
            json_decode($response->getContent(), true)
        );
    }

    /**
     * @return array<string, array<Exception, array{
     *      code: string,
     *      errorMessage: string
     *      errors?: array<string, string>
     * }>>
     */
    public static function dataBuildResponse(): array
    {
        return [
            'generic exception' => [
                new \Exception('Exception message'),
                [
                    'code' => 'exception',
                    'errorMessage' => 'Exception message'
                ]
            ],
            'validation exception' => [
                new ValidationException(['field' => 'error']),
                [
                    'code' => 'validation_exception',
                    'errorMessage' => 'Invalid request data.',
                    'errors' => ['field' => 'error']
                ]
            ],
            'domain exception' => [
                new TestDomainException(),
                [
                    'code' => 'test_domain',
                    'errorMessage' => 'Test error message'
                ]
            ]
        ];
    }

    #[DataProvider('dataGetErrorCode')]
    public function testItGetsErrorCode(\Throwable $exception, string $errorCode): void
    {
		$sut = new ApiExceptionListener(
            exceptionHttpStatusCodeMapper: $this->createStub(ExceptionHttpStatusCodeMapper::class)
        );

        $reflection = new \ReflectionClass($sut);
        $method = $reflection->getMethod('getErrorCode');

        $errorCode = $method->invoke($sut, $exception);
        $this->assertEquals($errorCode, $errorCode);
    }

    /**
     * @return array<string, array<Exception, string>>
     */
    public static function dataGetErrorCode(): array
    {
        return [
            'generic exception' => [
                new \Exception('Exception message'),
                'exception'
            ],
            'domain exception' => [
                new \Exception('Exception message'),
                'exception'
            ]
        ];
    }

    #[DataProvider('dataStatusCodes')]
    public function testItGetsStatusCode(\Throwable $exception, ?int $exceptionStatusCode): void
    {
		$exceptionHttpStatusCodeMapperMock = new ExceptionHttpStatusCodeMapperMock($this);

        $exceptionHttpStatusCodeMapperMock
            ->shouldGetStatusCodeFor($exception::class, $exceptionStatusCode);

		$sut = new ApiExceptionListener(
            exceptionHttpStatusCodeMapper: $exceptionHttpStatusCodeMapperMock->getMock()
        );

        $reflection = new \ReflectionClass($sut);
        $method = $reflection->getMethod('getStatusCode');

        $statusCode = $method->invoke($sut, $exception);
        $this->assertEquals(
            $exceptionStatusCode ?? HttpStatusCode::HTTP_INTERNAL_SERVER_ERROR->value,
            $statusCode
        );
    }

    /**
     * @return array<string, array<Exception, int|null>>
     */
    public static function dataStatusCodes(): array
    {
        return [
            'generic exception and defined status code' => [
                new \Exception('Exception message'),
                HttpStatusCode::HTTP_NOT_FOUND->value
            ],
            'validation exception and defined status code' => [
                new ValidationException(['field' => 'error']),
                HttpStatusCode::HTTP_BAD_REQUEST->value
            ],
            'undefined status code' => [
                new \Exception('Exception message'),
                null
            ]
        ];
    }
}
