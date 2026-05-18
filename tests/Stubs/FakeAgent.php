<?php

declare(strict_types=1);

namespace Descom\AwsBedrock\Tests\Stubs;

use Descom\AwsBedrock\Converse\Agent;

final class FakeAgent extends Agent
{
    protected string $modelId = 'anthropic.claude-3-haiku-20240307-v1:0';

    public function systemPrompt(): ?string
    {
        return 'You are a test agent.';
    }

    public function tools(): array
    {
        return [];
    }
}
