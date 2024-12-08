<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\TestCase;

use App\Shared\Domain\Contract\Http\HttpClient;
use App\Shared\Domain\Http\HttpHeader;
use App\Shared\Domain\Http\HttpHeaders;
use App\Shared\Infrastructure\Http\HttpResponse;
use App\Shared\Infrastructure\Http\TemporaryFileStream;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use PHPUnit\Framework\InvalidArgumentException;
use PHPUnit\Framework\MockObject\IncompatibleReturnValueException;
use PHPUnit\Framework\MockObject\MethodCannotBeConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameAlreadyConfiguredException;

final class HttpClientMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return HttpClient::class;
    }

    /**
	 * @param array<string, mixed> $content
     *
	 * @throws \RuntimeException
	 * @throws \InvalidArgumentException
     * @throws IncompatibleReturnValueException
     * @throws InvalidArgumentException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     */
    public function shouldFetchSuccessfully(
		int $times,
		array $content
	): void {
        $temporaryFileStream = new TemporaryFileStream(json_encode([
			'content' => $content,
			'error' => null,
		]));

		$httpHeaders = $times > 1
			? new HttpHeaders(
				new HttpHeader('Content-Type', 'application/json'),
				new HttpHeader(
					'Link',
					'<https://projects.com/user?page=2>; rel="next", <https://projects.com/user?page=2>; rel="last"'
				)
			)
			: new HttpHeaders(
				new HttpHeader('Content-Type', 'application/json'),
			);

        $httpResponse = HttpResponse::create(
            body: $temporaryFileStream,
            headers: $httpHeaders
        );

        $this->mock
            ->expects($this->exactly($times))
            ->method('fetch')
            ->willReturn($httpResponse);
    }
}
