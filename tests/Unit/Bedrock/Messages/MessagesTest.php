<?php

declare(strict_types=1);

namespace Descom\Ai\Tests\Unit\Bedrock\Messages;

use Descom\Ai\Bedrock\Messages\Contents\Contents;
use Descom\Ai\Bedrock\Messages\Contents\TextContent;
use Descom\Ai\Bedrock\Messages\Message;
use Descom\Ai\Bedrock\Messages\Messages;
use Descom\Ai\Bedrock\Messages\Role;
use Descom\Ai\Tests\TestCase;

final class MessagesTest extends TestCase
{
    public function test_add_message_appends_in_order(): void
    {
        $messages = new Messages;
        $first = new Message(Role::USER, new Contents([new TextContent('one')]));
        $second = new Message(Role::ASSISTANT, new Contents([new TextContent('two')]));

        $messages->addMessage($first);
        $messages->addMessage($second);

        $this->assertCount(2, $messages);
        $this->assertSame([$first, $second], $messages->all());
    }
}
