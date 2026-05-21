<?php

declare(strict_types=1);

namespace Descom\Ai\Bedrock\Messages\Response\Output;

use Descom\Ai\Bedrock\Messages\Role;

final class Message
{
    public readonly Role $role;

    /** @var Content[] */
    public readonly array $contents;

    private function __construct(array $data)
    {
        $this->role = Role::from($data['role']);
        $this->contents = array_map(
            fn (array $block) => Content::fromBlock($block),
            $data['content']
        );
    }

    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    public function text(): ?string
    {
        foreach ($this->contents as $content) {
            if ($content instanceof TextContent) {
                return $content->text;
            }
        }

        return null;
    }

    /** @return ToolUseContent[] */
    public function toolUses(): array
    {
        return array_values(array_filter(
            $this->contents,
            fn (Content $content) => $content instanceof ToolUseContent
        ));
    }
}
