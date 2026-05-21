<?php

declare(strict_types=1);

namespace Descom\Ai\Bedrock\Messages\Contents;

use Illuminate\Support\Collection;

final class Contents extends Collection
{
    public function addContent(Content $content)
    {
        $this->add($content);
    }

    // public function toArray(): array
    // {
    //     return $this->toArray();
    // }
}
