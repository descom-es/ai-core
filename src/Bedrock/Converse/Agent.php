<?php

namespace Descom\Ai\Bedrock\Converse;

use Descom\Ai\Bedrock\Messages\Contents\Contents;
use Descom\Ai\Bedrock\Messages\Contents\DocumentContent;
use Descom\Ai\Bedrock\Messages\Contents\ImageContent;
use Descom\Ai\Bedrock\Messages\Contents\Sources\BinarySource;
use Descom\Ai\Bedrock\Messages\Contents\TextContent;
use Descom\Ai\Bedrock\Messages\Message;
use Descom\Ai\Bedrock\Messages\Messages;
use Descom\Ai\Bedrock\Messages\Response\Response;
use Descom\Ai\Bedrock\Messages\Role;
use RuntimeException;

abstract class Agent
{
    protected BedrockClientConverse $client;

    protected string $modelId;

    public function __construct()
    {
        if (! isset($this->modelId)) {
            throw new RuntimeException('Model ID must be set in the agent '.get_class($this));
        }

        $this->client = new BedrockClientConverse($this);
    }

    abstract public function systemPrompt(): ?string;

    abstract public function tools(): array;

    public function modelId(): string
    {
        return $this->modelId;
    }

    public function ask(string $message): Response
    {
        $message = new Message(Role::USER, new Contents([new TextContent($message)]));

        $messages = new Messages([$message]);

        return $this->request($messages);
    }

    public function sendImage(string $filename, ?string $caption = null): Response
    {
        $format = str_replace('image/', '', mime_content_type($filename));
        $source = new BinarySource(file_get_contents($filename));
        $contents = new Contents([new ImageContent($format, $source)]);

        if ($caption) {
            $contents->add(new TextContent($caption));
        }

        $message = new Message(Role::USER, $contents);

        $messages = new Messages([$message]);

        return $this->request($messages);
    }

    public function sendDocument(string $filename, ?string $caption = null): Response
    {
        $format = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $name = pathinfo($filename, PATHINFO_FILENAME);
        $source = new BinarySource(file_get_contents($filename));
        $contents = new Contents([new DocumentContent($format, $name, $source)]);

        if ($caption) {
            $contents->add(new TextContent($caption));
        }

        $message = new Message(Role::USER, $contents);

        $messages = new Messages([$message]);

        return $this->request($messages);
    }

    public function request(Messages $messages): Response
    {
        return $this->client->request($messages);
    }
}
