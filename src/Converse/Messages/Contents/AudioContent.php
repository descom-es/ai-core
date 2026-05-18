<?php

declare(strict_types=1);

namespace Descom\AwsBedrock\Converse\Messages\Contents;

use Descom\AwsBedrock\Converse\Messages\Contents\Sources\Source;
use Descom\AwsBedrock\Converse\Messages\Contents\Validations\AudioExtension;

final class AudioContent extends Content
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
            'audio' => [
                'format' => $this->format,
                'source' => $this->source->toArray(),
            ],
        ];
    }

    private function validate()
    {
        if (! AudioExtension::valid($this->format)) {
            throw new \InvalidArgumentException("Unsupported audio format: {$this->format}");
        }
    }
}
