<?php

declare(strict_types=1);

namespace Descom\Ai\Tests\Stubs;

use Descom\Ai\Tools\ToolsContract;

final class FakeTool implements ToolsContract
{
    public static array $lastArguments = [];

    public static array $lastParameters = [];

    public function __construct(object|array $arguments)
    {
        self::$lastArguments = is_object($arguments)
            ? json_decode(json_encode($arguments), true)
            : $arguments;
    }

    public function run(object|array $parameters): bool|string|array
    {
        self::$lastParameters = is_object($parameters)
            ? json_decode(json_encode($parameters), true)
            : $parameters;

        return self::$lastArguments['return'] ?? 'fake-result';
    }
}
