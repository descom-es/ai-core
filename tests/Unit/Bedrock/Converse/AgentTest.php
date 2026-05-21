<?php

declare(strict_types=1);

namespace Descom\Ai\Tests\Unit\Bedrock\Converse;

use Descom\Ai\Tests\Stubs\FakeAgent;
use Descom\Ai\Tests\Stubs\FakeAgentWithoutModelId;
use Descom\Ai\Tests\TestCase;
use RuntimeException;

final class AgentTest extends TestCase
{
    public function test_constructor_succeeds_with_model_id(): void
    {
        $agent = new FakeAgent;

        $this->assertSame('anthropic.claude-3-haiku-20240307-v1:0', $agent->modelId());
    }

    public function test_constructor_throws_without_model_id(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Model ID must be set in the agent');

        new FakeAgentWithoutModelId;
    }

    public function test_system_prompt_and_tools_callable(): void
    {
        $agent = new FakeAgent;

        $this->assertSame('You are a test agent.', $agent->systemPrompt());
        $this->assertSame([], $agent->tools());
    }
}
