<?php

declare(strict_types=1);

namespace Descom\Ai\Tests\Unit\Bedrock\Messages\Response;

use Aws\Result;
use Descom\Ai\Bedrock\Messages\Response\Metrics;
use Descom\Ai\Bedrock\Messages\Response\Output;
use Descom\Ai\Bedrock\Messages\Response\Response;
use Descom\Ai\Bedrock\Messages\Response\StopReason;
use Descom\Ai\Bedrock\Messages\Response\Usage;
use Descom\Ai\Tests\TestCase;

final class ResponseTest extends TestCase
{
    private function buildResult(array $overrides = []): Result
    {
        return new Result(array_merge([
            'stopReason' => 'end_turn',
            'usage' => [
                'inputTokens' => 10,
                'outputTokens' => 20,
                'totalTokens' => 30,
            ],
            'metrics' => ['latencyMs' => 150],
            'output' => [
                'message' => [
                    'role' => 'assistant',
                    'content' => [['text' => 'hi']],
                ],
            ],
        ], $overrides));
    }

    public function test_stop_reason_returns_enum(): void
    {
        $response = new Response($this->buildResult());

        $this->assertSame(StopReason::EndTurn, $response->stopReason());
    }

    public function test_stop_reason_returns_null_when_missing(): void
    {
        $response = new Response(new Result([
            'usage' => ['inputTokens' => 1, 'outputTokens' => 1, 'totalTokens' => 2],
            'metrics' => ['latencyMs' => 1],
            'output' => ['message' => ['role' => 'assistant', 'content' => [['text' => 'x']]]],
        ]));

        $this->assertNull($response->stopReason());
    }

    public function test_stop_reason_by_tool_use_true(): void
    {
        $response = new Response($this->buildResult(['stopReason' => 'tool_use']));

        $this->assertTrue($response->stopReasonByToolUse());
    }

    public function test_stop_reason_by_tool_use_false(): void
    {
        $response = new Response($this->buildResult());

        $this->assertFalse($response->stopReasonByToolUse());
    }

    public function test_usage_returns_hydrated(): void
    {
        $response = new Response($this->buildResult());
        $usage = $response->usage();

        $this->assertInstanceOf(Usage::class, $usage);
        $this->assertSame(10, $usage->inputTokens);
    }

    public function test_metrics_returns_hydrated(): void
    {
        $response = new Response($this->buildResult());
        $metrics = $response->metrics();

        $this->assertInstanceOf(Metrics::class, $metrics);
        $this->assertSame(150, $metrics->latencyMs);
    }

    public function test_output_returns_hydrated(): void
    {
        $response = new Response($this->buildResult());

        $this->assertInstanceOf(Output::class, $response->output());
    }

    public function test_to_array_delegates_to_result(): void
    {
        $result = $this->buildResult();
        $response = new Response($result);

        $this->assertSame($result->toArray(), $response->toArray());
    }
}
