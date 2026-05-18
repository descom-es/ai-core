<?php

declare(strict_types=1);

namespace Descom\AwsBedrock\Tests\Unit\Tools;

use Descom\AwsBedrock\Tests\Stubs\FakeTool;
use Descom\AwsBedrock\Tests\TestCase;
use Descom\AwsBedrock\Tools\ExecuteTool;
use RuntimeException;

final class ExecuteToolTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        FakeTool::$lastArguments = [];
        FakeTool::$lastParameters = [];
    }

    public function test_constructor_preserves_name_and_tools(): void
    {
        $tools = [['name' => 'foo']];
        $action = new ExecuteTool('foo', $tools);

        $this->assertSame('foo', $action->name);
        $this->assertSame($tools, $action->tools);
    }

    public function test_execute_resolves_tool_and_calls_run(): void
    {
        $action = new ExecuteTool(
            name: 'weather',
            tools: [[
                'name' => 'weather',
                'tool_action' => [
                    'class' => FakeTool::class,
                    'arguments' => ['return' => 'sunny'],
                ],
            ]],
        );

        $result = $action->execute(['location' => 'Madrid']);

        $this->assertSame('sunny', $result);
        $this->assertSame(['return' => 'sunny'], FakeTool::$lastArguments);
        $this->assertSame(['location' => 'Madrid'], FakeTool::$lastParameters);
    }

    public function test_execute_throws_when_class_does_not_exist(): void
    {
        $action = new ExecuteTool(
            name: 'ghost',
            tools: [[
                'name' => 'ghost',
                'tool_action' => [
                    'class' => 'App\\Does\\Not\\Exist',
                    'arguments' => [],
                ],
            ]],
        );

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Tool class App\\Does\\Not\\Exist does not exist.');

        $action->execute([]);
    }
}
