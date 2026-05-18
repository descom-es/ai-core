<?php

declare(strict_types=1);

namespace Descom\AwsBedrock\Converse\Messages\Contents\Sources;

abstract class Source
{
    abstract public function toArray(): array;

    public static function fromArray(array $data): self
    {
        if (array_key_exists('uri', $data)) {
            $source = new S3Source($data['uri']);

            if (array_key_exists('accountId', $data)) {
                $source->withAccountId($data['accountId']);
            }

            return $source;
        }

        if (array_key_exists('bytes', $data)) {
            return new BinarySource($data['bytes']);
        }

        throw new \InvalidArgumentException('Unable to determine Source type from array: '.json_encode(array_keys($data)));
    }
}
