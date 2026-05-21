<?php

namespace Descom\Ai\Tools;

interface ToolsContract
{
    public function __construct(object|array $arguments);

    public function run(object|array $parameters): bool|string|array;
}
