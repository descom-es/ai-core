<?php

declare(strict_types=1);

namespace Descom\Ai\Tests\Unit\Bedrock\Messages\Contents;

use Descom\Ai\Bedrock\Messages\Contents\Contents;
use Descom\Ai\Bedrock\Messages\Contents\ImageContent;
use Descom\Ai\Bedrock\Messages\Contents\Sources\BinarySource;
use Descom\Ai\Bedrock\Messages\Contents\TextContent;
use Descom\Ai\Tests\TestCase;

final class ContentsTest extends TestCase
{
    public function test_add_content_appends_in_order(): void
    {
        $contents = new Contents;
        $first = new TextContent('first');
        $second = new ImageContent('png', new BinarySource('bytes'));

        $contents->addContent($first);
        $contents->addContent($second);

        $this->assertCount(2, $contents);
        $this->assertSame([$first, $second], $contents->all());
    }
}
