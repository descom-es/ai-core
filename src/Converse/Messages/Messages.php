<?php

declare(strict_types=1);

namespace Descom\AwsBedrock\Converse\Messages;

use Illuminate\Support\Collection;

final class Messages extends Collection
{
    public function addMessage(Message $message)
    {
        $this->add($message);
    }
}
