<?php

declare(strict_types=1);

namespace Descom\AwsBedrock\Converse\Messages\Response;

final class Usage
{
    public function __construct(
        public readonly int $inputTokens,
        public readonly int $outputTokens,
        public readonly int $totalTokens,
        public readonly ?int $cacheReadInputTokens = null,
        public readonly ?int $cacheWriteInputTokens = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            inputTokens: $data['inputTokens'],
            outputTokens: $data['outputTokens'],
            totalTokens: $data['totalTokens'],
            cacheReadInputTokens: $data['cacheReadInputTokens'] ?? null,
            cacheWriteInputTokens: $data['cacheWriteInputTokens'] ?? null,
        );
    }
}
