<?php

declare(strict_types=1);

namespace Descom\AwsBedrock\Converse\Messages\Contents;

use Descom\AwsBedrock\Converse\Messages\Contents\Sources\Source;
use Descom\AwsBedrock\Converse\Messages\Contents\Validations\DocumentExtension;

final class DocumentContent extends Content
{
    public function __construct(
        public readonly string $format,
        public readonly string $name,
        public readonly Source $source,
    ) {
        $this->validate();
    }

    public function toArray(): array
    {
        return [
            'document' => [
                'format' => $this->format,
                'name' => $this->name,
                'source' => $this->source->toArray(),
            ],
        ];
    }

    private function validate()
    {
        if (! DocumentExtension::valid($this->format)) {
            throw new \InvalidArgumentException("Unsupported document format: {$this->format}");
        }
    }
}
