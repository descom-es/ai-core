<?php

declare(strict_types=1);

namespace Descom\AwsBedrock\Converse\Messages\Response;

use Descom\AwsBedrock\Converse\Messages\Response\Output\Message;

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
