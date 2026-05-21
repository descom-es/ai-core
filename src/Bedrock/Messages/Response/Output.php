<?php

declare(strict_types=1);

namespace Descom\Ai\Bedrock\Messages\Response;

use Descom\Ai\Bedrock\Messages\Response\Output\Message;

final class Output
{
    public readonly Message $message;

    private function __construct(array $data)
    {
        $this->message = Message::fromArray($data['message']);
    }

    public static function fromArray(array $data): self
    {
        return new self($data);
    }
}
