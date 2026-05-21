<?php

declare(strict_types=1);

namespace Descom\Ai\Bedrock\Messages\Response\Output;

final class ToolUseContent extends Content
{
    public function __construct(
        public readonly string $toolUseId,
        public readonly string $name,
        public readonly array $input,
    ) {}

    public function toArray(): array
    {
        return [
            'toolUse' => [
                'toolUseId' => $this->toolUseId,
                'name' => $this->name,
                'input' => $this->sanitizeInput($this->input),
            ],
        ];
    }

    private function sanitizeInput(array $input): array|object
    {
        if ($input) {
            return $input;
        }

        return new \stdClass;
    }
}
