<?php

declare(strict_types=1);

namespace Descom\Ai\Bedrock\Messages\Contents;

use Descom\Ai\Bedrock\Messages\Contents\Sources\Source;
use Descom\Ai\Bedrock\Messages\Contents\Validations\ImageExtension;

final class ImageContent extends Content
{
    public function __construct(
        public readonly string $format,
        public readonly Source $source,
    ) {
        $this->validate();
    }

    public function toArray(): array
    {
        return [
            'image' => [
                'format' => $this->format,
                'source' => $this->source->toArray(),
            ],
        ];
    }

    private function validate()
    {
        if (! ImageExtension::valid($this->format)) {
            throw new \InvalidArgumentException("Unsupported image format: {$this->format}");
        }
    }
}
