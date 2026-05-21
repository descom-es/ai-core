<?php

declare(strict_types=1);

namespace Descom\Ai\Bedrock\Messages\Response\Output;

abstract class Content
{
    abstract public function toArray(): array;

    public static function fromBlock(array $block): self
    {
        return match (true) {
            isset($block['text']) => new TextContent($block['text']),
            isset($block['toolUse']) => new ToolUseContent(
                toolUseId: $block['toolUse']['toolUseId'],
                name: $block['toolUse']['name'],
                input: (array) $block['toolUse']['input'],
            ),
            default => new AnonymousContent($block),
        };
    }
}
