<?php

declare(strict_types=1);

namespace Descom\Ai\Tests\Unit\Bedrock\Messages\Response;

use Descom\Ai\Bedrock\Messages\Response\StopReason;
use Descom\Ai\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

final class StopReasonTest extends TestCase
{
    /**
     * @return array<string, array{0: string, 1: StopReason}>
     */
    public static function validValues(): array
    {
        return [
            'end_turn' => ['end_turn', StopReason::EndTurn],
            'tool_use' => ['tool_use', StopReason::ToolUse],
            'max_tokens' => ['max_tokens', StopReason::MaxTokens],
            'stop_sequence' => ['stop_sequence', StopReason::StopSequence],
            'guardrail_intervened' => ['guardrail_intervened', StopReason::GuardrailIntervened],
            'content_filtered' => ['content_filtered', StopReason::ContentFiltered],
            'malformed_model_output' => ['malformed_model_output', StopReason::MalformedModelOutput],
            'malformed_tool_use' => ['malformed_tool_use', StopReason::MalformedToolUse],
            'model_context_window_exceeded' => ['model_context_window_exceeded', StopReason::ModelContextWindowExceeded],
        ];
    }

    #[DataProvider('validValues')]
    public function test_from_valid_value(string $raw, StopReason $expected): void
    {
        $this->assertSame($expected, StopReason::from($raw));
    }

    public function test_from_invalid_throws(): void
    {
        $this->expectException(\ValueError::class);

        StopReason::from('not_a_reason');
    }
}
