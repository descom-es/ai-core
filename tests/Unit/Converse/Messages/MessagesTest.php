<?php

declare(strict_types=1);

namespace Descom\AwsBedrock\Tests\Unit\Converse\Messages;

use Descom\AwsBedrock\Converse\Messages\Contents\Contents;
use Descom\AwsBedrock\Converse\Messages\Contents\TextContent;
use Descom\AwsBedrock\Converse\Messages\Message;
use Descom\AwsBedrock\Converse\Messages\Messages;
use Descom\AwsBedrock\Converse\Messages\Role;
use Descom\AwsBedrock\Tests\TestCase;

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
