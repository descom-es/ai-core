<?php

declare(strict_types=1);

namespace Descom\Ai\Bedrock\Messages\Contents\Sources;

final class BinarySource extends Source
{
    public function __construct(
        public readonly string $bytes,
    ) {}

    public function toArray(): array
    {
        return [
            'bytes' => $this->bytes,
        ];
    }
}
