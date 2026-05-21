<?php

declare(strict_types=1);

namespace Descom\Ai\Bedrock\Messages\Contents\Sources;

final class S3Source extends Source
{
    private ?string $accountId = null;

    public function __construct(
        public readonly string $uri,
    ) {}

    public function withAccountId(string $accountId): self
    {
        $this->accountId = $accountId;

        return $this;
    }

    public function toArray(): array
    {
        return array_merge([
            'uri' => $this->uri,
        ], $this->accountId !== null ? ['accountId' => $this->accountId] : []);
    }
}
