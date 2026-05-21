<?php

namespace Descom\Ai\Bedrock\Converse\Conversations;

abstract class ConversationsRepository
{
    abstract public function create(): ConversationRepository;

    abstract public function find(string $identifier): ConversationRepository;
}
