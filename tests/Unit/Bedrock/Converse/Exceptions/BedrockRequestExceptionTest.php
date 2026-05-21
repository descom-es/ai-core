<?php

declare(strict_types=1);

namespace Descom\Ai\Tests\Unit\Bedrock\Converse\Exceptions;

use Descom\Ai\Bedrock\Converse\Exceptions\BedrockRequestException;
use Descom\Ai\Tests\TestCase;
use RuntimeException;

final class BedrockRequestExceptionTest extends TestCase
{
    public function test_preserves_payload(): void
    {
        $payload = ['modelId' => 'foo', 'messages' => []];
        $previous = new RuntimeException('boom');

        $e = new BedrockRequestException($payload, $previous);

        $this->assertSame($payload, $e->payload);
    }

    public function test_chains_previous(): void
    {
        $previous = new RuntimeException('boom');
        $e = new BedrockRequestException([], $previous);

        $this->assertSame($previous, $e->getPrevious());
    }

    public function test_message_reflects_previous_message(): void
    {
        $previous = new RuntimeException('upstream failure');
        $e = new BedrockRequestException([], $previous);

        $this->assertSame('upstream failure', $e->getMessage());
        $this->assertSame(0, $e->getCode());
    }
}
