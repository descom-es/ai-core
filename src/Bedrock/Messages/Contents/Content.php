<?php

declare(strict_types=1);

namespace Descom\Ai\Bedrock\Messages\Contents;

use Descom\Ai\Bedrock\Messages\Contents\Sources\Source;

abstract class Content
{
    abstract public function toArray(): array;

    public static function fromArray(array $data): self
    {
        if (array_key_exists('text', $data)) {
            return new TextContent($data['text']);
        }

        if (array_key_exists('image', $data)) {
            return new ImageContent(
                format: $data['image']['format'],
                source: Source::fromArray($data['image']['source']),
            );
        }

        if (array_key_exists('document', $data)) {
            return new DocumentContent(
                format: $data['document']['format'],
                name: $data['document']['name'],
                source: Source::fromArray($data['document']['source']),
            );
        }

        if (array_key_exists('audio', $data)) {
            return new AudioContent(
                format: $data['audio']['format'],
                source: Source::fromArray($data['audio']['source']),
            );
        }

        throw new \InvalidArgumentException('Unable to determine Content type from array: '.json_encode(array_keys($data)));
    }
}
