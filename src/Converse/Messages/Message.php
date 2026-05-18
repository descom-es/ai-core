<?php

declare(strict_types=1);

namespace Descom\AwsBedrock\Converse\Messages;

use Descom\AwsBedrock\Converse\Messages\Contents\Content;
use Descom\AwsBedrock\Converse\Messages\Contents\Contents;

final class Message
{
    public function __construct(
        public readonly Role $role,
        public readonly Contents $contents,
    ) {}

    public function toArray(): array
    {
        return [
            'role' => $this->role->value,
            'content' => $this->contents->map(fn ($content) => is_array($content) ? $content : $content->toArray())->toArray(),
        ];
    }

    public static function fromArray(array $data): self
    {
        $contents = new Contents;

        foreach ($data['content'] as $contentData) {
            $contents->addContent(Content::fromArray($contentData));
        }

        return new self(
            role: Role::from($data['role']),
            contents: $contents,
        );
    }
}
