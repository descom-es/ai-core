<?php

declare(strict_types=1);

namespace Descom\AwsBedrock\Converse\Messages\Response\Output;

final class TextContent extends Content
{
    public function __construct(
        public readonly string $text,
    ) {}

    public function toArray(): array
    {
        return ['text' => $this->text];
    }
}
