<?php

declare(strict_types=1);

namespace Descom\Ai\Bedrock\Messages\Response;

enum StopReason: string
{
    case EndTurn = 'end_turn';
    case ToolUse = 'tool_use';
    case MaxTokens = 'max_tokens';
    case StopSequence = 'stop_sequence';
    case GuardrailIntervened = 'guardrail_intervened';
    case ContentFiltered = 'content_filtered';
    case MalformedModelOutput = 'malformed_model_output';
    case MalformedToolUse = 'malformed_tool_use';
    case ModelContextWindowExceeded = 'model_context_window_exceeded';
}
