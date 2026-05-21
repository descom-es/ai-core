<?php

declare(strict_types=1);

namespace Descom\Ai\Tests\Unit\Bedrock\Messages\Response\Output;

use Descom\Ai\Bedrock\Messages\Response\Output\TextContent;
use Descom\Ai\Tests\TestCase;

final class TextContentTest extends TestCase
{
    public function test_constructor_and_to_array(): void
    {
        $content = new TextContent('hello');

        $this->assertSame('hello', $content->text);
        $this->assertSame(['text' => 'hello'], $content->toArray());
    }
}
