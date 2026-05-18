<?php

namespace Descom\AwsBedrock\Converse\Conversations;

use Descom\AwsBedrock\Converse\Messages\Message;
use Descom\AwsBedrock\Converse\Messages\Messages;

abstract class ConversationRepository
{
    public function __construct(public readonly string $identifier) {}

    abstract public function messages(): Messages;

    abstract public function addMessage(Message $message);

    abstract public function close(): bool;

    public function addMessages(Messages $messages)
    {
        foreach ($messages as $message) {
            $this->addMessage($message);
        }
    }
}
