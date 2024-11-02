<?php

declare(strict_types=1);

namespace App\Tests\Unit\UI\Subscriber;

use App\Shared\Domain\Http\HttpStatusCode;
use App\Tests\Unit\Shared\Domain\Testing\TestDomainException;
use App\Tests\Unit\UI\TestCase\ExceptionEventMock;
use App\Tests\Unit\UI\TestCase\ExceptionHttpStatusCodeMapperMock;
use App\UI\Exception\ValidationException;
use App\UI\Subscriber\ApiExceptionListener;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

final class ApiExceptionListenerTest extends TestCase
{
    private ?ExceptionHttpStatusCodeMapperMock $exceptionHttpStatusCodeMapperMock;
    private ?ExceptionEventMock $exceptionEventMock;
    private ?ApiExceptionListener $sut;

    protected function setUp(): void
    {
        $this->exceptionHttpStatusCodeMapperMock = new ExceptionHttpStatusCodeMapperMock();
        $this->exceptionEventMock = new ExceptionEventMock();
        $this->sut = new ApiExceptionListener(
            exceptionHttpStatusCodeMapper: $this->exceptionHttpStatusCodeMapperMock->getMock()
        );
    }

    protected function tearDown(): void
    {
        $this->exceptionHttpStatusCodeMapperMock = null;
        $this->exceptionEventMock = null;
        $this->sut = null;
    }

    #[DataProvider('dataIsMainRequest')]
    public function testOnKernelException(bool $isMainRequest): void
    {
        $this->exceptionEventMock
            ->shouldGetThrowable(new \Exception('Exception message'));

        $this->exceptionEventMock
            ->shouldBeMainRequest($isMainRequest);

        $this->exceptionEventMock
            ->shouldCallSetResponse((int) $isMainRequest);

        $result = $this->sut->onKernelException($this->exceptionEventMock->getMock());
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

    #[DataProvider('dataBuildResponse')]
    /**
     * @param array{
     *      code: string,
     *      errorMessage: string
     *      errors?: array<string, string>
     * } $exceptionContent
     */
    public function testBuildResponse(\Throwable $exception, array $exceptionContent): void
    {
        $reflection = new \ReflectionClass($this->sut);
        $method = $reflection->getMethod('buildResponse');
        $method->setAccessible(true);

        $response = $method->invoke($this->sut, $exception);
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
    public function testGetErrorCode(\Throwable $exception, string $errorCode): void
    {
        $reflection = new \ReflectionClass($this->sut);
        $method = $reflection->getMethod('getErrorCode');
        $method->setAccessible(true);

        $errorCode = $method->invoke($this->sut, $exception);
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
    public function testGetStatusCode(\Throwable $exception, ?int $exceptionStatusCode): void
    {
        $this->exceptionHttpStatusCodeMapperMock
            ->shouldGetStatusCodeFor($exception::class, $exceptionStatusCode);

        $reflection = new \ReflectionClass($this->sut);
        $method = $reflection->getMethod('getStatusCode');
        $method->setAccessible(true);

        $statusCode = $method->invoke($this->sut, $exception);
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
