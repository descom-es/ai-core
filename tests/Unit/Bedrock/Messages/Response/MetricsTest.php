<?php

declare(strict_types=1);

namespace Descom\Ai\Tests\Unit\Bedrock\Messages\Response;

use Descom\Ai\Bedrock\Messages\Response\Metrics;
use Descom\Ai\Tests\TestCase;

final class MetricsTest extends TestCase
{
    public function test_constructor_preserves_latency(): void
    {
        $metrics = new Metrics(latencyMs: 123);

        $this->assertSame(123, $metrics->latencyMs);
    }

    public function test_from_array(): void
    {
        $metrics = Metrics::fromArray(['latencyMs' => 456]);

        $this->assertSame(456, $metrics->latencyMs);
    }
}
