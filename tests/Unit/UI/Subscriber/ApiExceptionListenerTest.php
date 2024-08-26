<?php

declare(strict_types=1);

namespace App\Tests\Unit\UI\Subscriber;

use App\Tests\Unit\UI\TestCase\ExceptionEventMock;
use App\Tests\Unit\UI\TestCase\ExceptionHttpStatusCodeMapperMock;
use App\UI\Subscriber\ApiExceptionListener;
use Exception;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

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
            ->shouldGetThrowable(new Exception('Exception message'));

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
            'is main request' => [true],
            'is not main request' => [false]
        ];
    }

    #[DataProvider('dataStatusCodes')]
    public function testGetStatusCode(Throwable $exception, ?int $exceptionStatusCode): void
    {
        $this->exceptionHttpStatusCodeMapperMock
            ->shouldGetStatusCodeFor($exception::class, $exceptionStatusCode);

        $reflection = new ReflectionClass($this->sut);
        $method = $reflection->getMethod('getStatusCode');
        $method->setAccessible(true);

        $statusCode = $method->invoke($this->sut, $exception);
        $this->assertEquals(
            $exceptionStatusCode ?? Response::HTTP_INTERNAL_SERVER_ERROR,
            $statusCode
        );
    }

    /**
     * @return array<string, array<Exception, int|null>>
     */
    public static function dataStatusCodes(): array
    {
        return [
            'defined status code and generic exception' => [
                new Exception('Exception message'),
                Response::HTTP_NOT_FOUND
            ],
            'defined status code and http exception' => [
                new HttpException(Response::HTTP_CONFLICT),
                Response::HTTP_CONFLICT
            ],
            'undefined status code' => [
                new Exception('Exception message'),
                null
            ]
        ];
    }
}
