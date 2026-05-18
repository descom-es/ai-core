<?php

declare(strict_types=1);

namespace Descom\AwsBedrock\Tests\Unit\Converse\Messages\Contents;

use Descom\AwsBedrock\Converse\Messages\Contents\Contents;
use Descom\AwsBedrock\Converse\Messages\Contents\ImageContent;
use Descom\AwsBedrock\Converse\Messages\Contents\Sources\BinarySource;
use Descom\AwsBedrock\Converse\Messages\Contents\TextContent;
use Descom\AwsBedrock\Tests\TestCase;

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
