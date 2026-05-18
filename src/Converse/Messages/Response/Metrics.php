<?php

declare(strict_types=1);

namespace Descom\AwsBedrock\Converse\Messages\Response;

final class Metrics
{
    public function __construct(
        public readonly int $latencyMs,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            latencyMs: $data['latencyMs'],
        );
    }
}
