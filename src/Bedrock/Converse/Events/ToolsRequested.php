<?php

namespace Descom\Ai\Bedrock\Converse\Events;

class ToolsRequested
{
    public function __construct(
        public array $toolRequests,
    ) {}
}
