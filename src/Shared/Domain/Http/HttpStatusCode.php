<?php

declare(strict_types=1);

namespace App\Shared\Domain\Http;

use App\Shared\Domain\Trait\EnumValuesProvider;

enum HttpStatusCode: int
{
    use EnumValuesProvider;

    case HTTP_CONTINUE = 100;
    case HTTP_SWITCHING_PROTOCOLS = 101;
    case HTTP_PROCESSING = 102;
    case HTTP_EARLY_HINTS = 103;
    case HTTP_OK = 200;
    case HTTP_CREATED = 201;
    case HTTP_ACCEPTED = 202;
    case HTTP_NON_AUTHORITATIVE_INFORMATION = 203;
    case HTTP_NO_CONTENT = 204;
    case HTTP_RESET_CONTENT = 205;
    case HTTP_PARTIAL_CONTENT = 206;
    case HTTP_MULTI_STATUS = 207;
    case HTTP_ALREADY_REPORTED = 208;
    case HTTP_IM_USED = 226;
    case HTTP_MULTIPLE_CHOICES = 300;
    case HTTP_MOVED_PERMANENTLY = 301;
    case HTTP_FOUND = 302;
    case HTTP_SEE_OTHER = 303;
    case HTTP_NOT_MODIFIED = 304;
    case HTTP_USE_PROXY = 305;
    case HTTP_RESERVED = 306;
    case HTTP_TEMPORARY_REDIRECT = 307;
    case HTTP_PERMANENTLY_REDIRECT = 308;
    case HTTP_BAD_REQUEST = 400;
    case HTTP_UNAUTHORIZED = 401;
    case HTTP_PAYMENT_REQUIRED = 402;
    case HTTP_FORBIDDEN = 403;
    case HTTP_NOT_FOUND = 404;
    case HTTP_METHOD_NOT_ALLOWED = 405;
    case HTTP_NOT_ACCEPTABLE = 406;
    case HTTP_PROXY_AUTHENTICATION_REQUIRED = 407;
    case HTTP_REQUEST_TIMEOUT = 408;
    case HTTP_CONFLICT = 409;
    case HTTP_GONE = 410;
    case HTTP_LENGTH_REQUIRED = 411;
    case HTTP_PRECONDITION_FAILED = 412;
    case HTTP_REQUEST_ENTITY_TOO_LARGE = 413;
    case HTTP_REQUEST_URI_TOO_LONG = 414;
    case HTTP_UNSUPPORTED_MEDIA_TYPE = 415;
    case HTTP_REQUESTED_RANGE_NOT_SATISFIABLE = 416;
    case HTTP_EXPECTATION_FAILED = 417;
    case HTTP_I_AM_A_TEAPOT = 418;
    case HTTP_MISDIRECTED_REQUEST = 421;
    case HTTP_UNPROCESSABLE_ENTITY = 422;
    case HTTP_LOCKED = 423;
    case HTTP_FAILED_DEPENDENCY = 424;
    case HTTP_TOO_EARLY = 425;
    case HTTP_UPGRADE_REQUIRED = 426;
    case HTTP_PRECONDITION_REQUIRED = 428;
    case HTTP_TOO_MANY_REQUESTS = 429;
    case HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE = 431;
    case HTTP_UNAVAILABLE_FOR_LEGAL_REASONS = 451;
    case HTTP_INTERNAL_SERVER_ERROR = 500;
    case HTTP_NOT_IMPLEMENTED = 501;
    case HTTP_BAD_GATEWAY = 502;
    case HTTP_SERVICE_UNAVAILABLE = 503;
    case HTTP_GATEWAY_TIMEOUT = 504;
    case HTTP_VERSION_NOT_SUPPORTED = 505;
    case HTTP_VARIANT_ALSO_NEGOTIATES = 506;
    case HTTP_INSUFFICIENT_STORAGE = 507;
    case HTTP_LOOP_DETECTED = 508;
    case HTTP_NOT_EXTENDED = 510;
    case HTTP_NETWORK_AUTHENTICATION_REQUIRED = 511;

    public function getReasonPhraseFromCode(): string
    {
        $reasonPhrase = $this->name;
        $reasonPhrase = str_replace('HTTP_', '', $reasonPhrase);

        return ucwords(str_replace('_', ' ', $reasonPhrase));
    }
}
