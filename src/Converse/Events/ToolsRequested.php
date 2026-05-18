<?php

namespace Descom\AwsBedrock\Converse\Events;

class ToolsRequested
{
    public function __construct(
        public array $toolRequests,
    ) {}
}
