<?php

declare(strict_types=1);

namespace Descom\Ai\Tests\Unit\Bedrock\Messages\Response\Output;

use Descom\Ai\Bedrock\Messages\Response\Output\Message;
use Descom\Ai\Bedrock\Messages\Response\Output\TextContent;
use Descom\Ai\Bedrock\Messages\Response\Output\ToolUseContent;
use Descom\Ai\Bedrock\Messages\Role;
use Descom\Ai\Tests\TestCase;

final class MessageTest extends TestCase
{
    public function test_from_array_assigns_role_and_contents(): void
    {
        $message = Message::fromArray([
            'role' => 'assistant',
            'content' => [['text' => 'hi'], ['text' => 'there']],
        ]);

        $this->assertSame(Role::ASSISTANT, $message->role);
        $this->assertCount(2, $message->contents);
    }

    public function test_text_returns_first_text_content(): void
    {
        $message = Message::fromArray([
            'role' => 'assistant',
            'content' => [['text' => 'first'], ['text' => 'second']],
        ]);

        $this->assertSame('first', $message->text());
    }

    public function test_text_returns_null_when_no_text_content(): void
    {
        $message = Message::fromArray([
            'role' => 'assistant',
            'content' => [
                ['toolUse' => ['toolUseId' => 'id', 'name' => 'foo', 'input' => []]],
            ],
        ]);

        $this->assertNull($message->text());
    }

    public function test_tool_uses_returns_only_tool_use_content_reindexed(): void
    {
        $message = Message::fromArray([
            'role' => 'assistant',
            'content' => [
                ['text' => 'skip'],
                ['toolUse' => ['toolUseId' => 'a', 'name' => 'one', 'input' => ['k' => 1]]],
                ['text' => 'skip too'],
                ['toolUse' => ['toolUseId' => 'b', 'name' => 'two', 'input' => []]],
            ],
        ]);

        $toolUses = $message->toolUses();

        $this->assertCount(2, $toolUses);
        $this->assertContainsOnlyInstancesOf(ToolUseContent::class, $toolUses);
        $this->assertSame([0, 1], array_keys($toolUses));
        $this->assertSame('a', $toolUses[0]->toolUseId);
        $this->assertSame('b', $toolUses[1]->toolUseId);
    }

    public function test_tool_uses_empty_when_none(): void
    {
        $message = Message::fromArray([
            'role' => 'assistant',
            'content' => [['text' => 'just text']],
        ]);

        $this->assertSame([], $message->toolUses());
    }

    public function test_contents_are_instances_of_text_content(): void
    {
        $message = Message::fromArray([
            'role' => 'assistant',
            'content' => [['text' => 'hi']],
        ]);

        $this->assertInstanceOf(TextContent::class, $message->contents[0]);
    }
}
