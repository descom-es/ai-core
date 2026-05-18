<?php

declare(strict_types=1);

namespace Descom\AwsBedrock\Converse\Messages;

enum Role: string
{
    case ASSISTANT = 'assistant';
    case USER = 'user';
}
