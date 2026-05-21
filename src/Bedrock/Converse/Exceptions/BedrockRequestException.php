<?php

declare(strict_types=1);

namespace Descom\Ai\Bedrock\Converse\Exceptions;

use RuntimeException;
use Throwable;

final class BedrockRequestException extends RuntimeException
{
    public function __construct(
        public readonly array $payload,
        Throwable $previous,
    ) {
        parent::__construct($previous->getMessage(), 0, $previous);
    }
}
