<?php

declare(strict_types=1);

namespace Descom\Ai\Tests\Unit\Bedrock\Converse\Events;

use Descom\Ai\Bedrock\Converse\Events\ToolsRequested;
use Descom\Ai\Tests\TestCase;

final class ToolsRequestedTest extends TestCase
{
    public function test_constructor_preserves_tool_requests(): void
    {
        $requests = [['name' => 'weather', 'input' => ['city' => 'Madrid']]];

        $event = new ToolsRequested($requests);

        $this->assertSame($requests, $event->toolRequests);
    }
}
