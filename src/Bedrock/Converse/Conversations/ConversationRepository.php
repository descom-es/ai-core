<?php

namespace Descom\Ai\Bedrock\Converse\Conversations;

use Descom\Ai\Bedrock\Messages\Message;
use Descom\Ai\Bedrock\Messages\Messages;

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
