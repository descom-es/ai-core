<?php

namespace Descom\AwsBedrock\Tools;

use RuntimeException;

class ExecuteTool
{
    public function __construct(
        public readonly string $name,
        public readonly array $tools = [],
    ) {}

    public function execute(array $input)
    {
        $toolAction = collect($this->tools)->firstWhere('name', $this->name)['tool_action'];

        $class = $toolAction['class'];
        $arguments = $toolAction['arguments'] ?? [];

        if (! class_exists($class)) {
            throw new RuntimeException("Tool class {$class} does not exist.");
        }

        $action = new $class($arguments);

        return $action->run($input);
    }
}
