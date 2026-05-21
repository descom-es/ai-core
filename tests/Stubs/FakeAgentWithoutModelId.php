<?php

declare(strict_types=1);

namespace Descom\Ai\Tests\Stubs;

use Descom\Ai\Bedrock\Converse\Agent;

final class FakeAgentWithoutModelId extends Agent
{
    public function systemPrompt(): ?string
    {
        return null;
    }

    public function tools(): array
    {
        return [];
    }
}
