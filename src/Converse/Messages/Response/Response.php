<?php

namespace Descom\AwsBedrock\Converse\Messages\Response;

use Aws\Result;

class Response
{
    public function __construct(public Result $result) {}

    public function stopReasonByToolUse(): bool
    {
        return $this->stopReason() === StopReason::ToolUse;
    }

    public function stopReason(): ?StopReason
    {
        $value = $this->result['stopReason'] ?? null;

        return $value ? StopReason::from($value) : null;
    }

    public function usage(): Usage
    {
        return Usage::fromArray($this->result['usage']);
    }

    public function metrics(): Metrics
    {
        return Metrics::fromArray($this->result['metrics']);
    }

    public function output(): Output
    {
        return Output::fromArray($this->result['output']);
    }

    public function toArray(): array
    {
        return $this->result->toArray();
    }
}
