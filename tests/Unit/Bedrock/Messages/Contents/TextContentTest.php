<?php

declare(strict_types=1);

namespace Descom\Ai\Tests\Unit\Bedrock\Messages\Contents;

use Descom\Ai\Bedrock\Messages\Contents\Content;
use Descom\Ai\Bedrock\Messages\Contents\TextContent;
use Descom\Ai\Tests\TestCase;

final class TextContentTest extends TestCase
{
    public function test_constructor_preserves_text(): void
    {
        $content = new TextContent('hello');

        $this->assertSame('hello', $content->text);
    }

    public function test_to_array(): void
    {
        $content = new TextContent('hi');

        $this->assertSame(['text' => 'hi'], $content->toArray());
    }

    public function test_roundtrip_via_factory(): void
    {
        $original = new TextContent('roundtrip');

        $restored = Content::fromArray($original->toArray());

        $this->assertInstanceOf(TextContent::class, $restored);
        $this->assertSame('roundtrip', $restored->text);
    }
}
