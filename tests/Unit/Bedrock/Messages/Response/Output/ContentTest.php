<?php

declare(strict_types=1);

namespace Descom\Ai\Tests\Unit\Bedrock\Messages\Response\Output;

use Descom\Ai\Bedrock\Messages\Response\Output\AnonymousContent;
use Descom\Ai\Bedrock\Messages\Response\Output\Content;
use Descom\Ai\Bedrock\Messages\Response\Output\TextContent;
use Descom\Ai\Bedrock\Messages\Response\Output\ToolUseContent;
use Descom\Ai\Tests\TestCase;

final class ContentTest extends TestCase
{
    public function test_from_block_text(): void
    {
        $content = Content::fromBlock(['text' => 'hello']);

        $this->assertInstanceOf(TextContent::class, $content);
        $this->assertSame('hello', $content->text);
    }

    public function test_from_block_tool_use(): void
    {
        $content = Content::fromBlock([
            'toolUse' => [
                'toolUseId' => 'tu_1',
                'name' => 'weather',
                'input' => ['location' => 'Madrid'],
            ],
        ]);

        $this->assertInstanceOf(ToolUseContent::class, $content);
        $this->assertSame('tu_1', $content->toolUseId);
        $this->assertSame('weather', $content->name);
        $this->assertSame(['location' => 'Madrid'], $content->input);
    }

    public function test_from_block_unknown_falls_back_to_anonymous(): void
    {
        $block = ['somethingElse' => ['arbitrary' => 'data']];

        $content = Content::fromBlock($block);

        $this->assertInstanceOf(AnonymousContent::class, $content);
        $this->assertSame($block, $content->toArray());
    }
}
