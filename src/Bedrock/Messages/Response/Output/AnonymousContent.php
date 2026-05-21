<?php

declare(strict_types=1);

namespace Descom\Ai\Bedrock\Messages\Response\Output;

final class AnonymousContent extends Content
{
    public function __construct(
        private readonly array $data,
    ) {}

    public function toArray(): array
    {
        return $this->data;
    }
}
