<?php

declare(strict_types=1);

namespace App\UI\Exception;

use App\Shared\Domain\ValueObject\Http\HttpStatusCode;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ValidationException extends HttpException
{
    public function __construct(
        /** @var array <string, string> */
        private readonly array $errors = []
    ) {
        parent::__construct(
            HttpStatusCode::HTTP_BAD_REQUEST->value,
            'Invalid request data.',
        );
    }

    /** @return array <string, string> */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
