<?php

declare(strict_types=1);

namespace Descom\Ai\Bedrock\Messages;

enum Role: string
{
    case ASSISTANT = 'assistant';
    case USER = 'user';
}
