<?php

declare(strict_types=1);

namespace Descom\AwsBedrock\Tests\Unit\Converse\Messages\Response\Output;

use Descom\AwsBedrock\Converse\Messages\Response\Output\AnonymousContent;
use Descom\AwsBedrock\Tests\TestCase;

final class AnonymousContentTest extends TestCase
{
    public function test_to_array_returns_original_data(): void
    {
        $payload = ['weirdKey' => ['nested' => true]];

        $content = new AnonymousContent($payload);

        $this->assertSame($payload, $content->toArray());
    }
}
