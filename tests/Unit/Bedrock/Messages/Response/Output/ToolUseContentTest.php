<?php

declare(strict_types=1);

namespace Descom\Ai\Tests\Unit\Bedrock\Messages\Response\Output;

use Descom\Ai\Bedrock\Messages\Response\Output\ToolUseContent;
use Descom\Ai\Tests\TestCase;

final class ToolUseContentTest extends TestCase
{
    public function test_constructor_preserves_fields(): void
    {
        $content = new ToolUseContent('tu_1', 'weather', ['location' => 'Madrid']);

        $this->assertSame('tu_1', $content->toolUseId);
        $this->assertSame('weather', $content->name);
        $this->assertSame(['location' => 'Madrid'], $content->input);
    }

    public function test_to_array_with_non_empty_input(): void
    {
        $content = new ToolUseContent('tu_1', 'weather', ['location' => 'Madrid']);

        $this->assertSame([
            'toolUse' => [
                'toolUseId' => 'tu_1',
                'name' => 'weather',
                'input' => ['location' => 'Madrid'],
            ],
        ], $content->toArray());
    }

    public function test_to_array_with_empty_input_uses_stdclass(): void
    {
        $content = new ToolUseContent('tu_2', 'noop', []);

        $array = $content->toArray();

        $this->assertInstanceOf(\stdClass::class, $array['toolUse']['input']);
        $this->assertSame('{"toolUse":{"toolUseId":"tu_2","name":"noop","input":{}}}', json_encode($array));
    }
}
