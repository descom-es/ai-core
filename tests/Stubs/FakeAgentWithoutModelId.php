<?php

declare(strict_types=1);

namespace Descom\AwsBedrock\Tests\Stubs;

use Descom\AwsBedrock\Converse\Agent;

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
