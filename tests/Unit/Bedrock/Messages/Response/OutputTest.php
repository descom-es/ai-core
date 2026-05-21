<?php

declare(strict_types=1);

namespace Descom\Ai\Tests\Unit\Bedrock\Messages\Response;

use Descom\Ai\Bedrock\Messages\Response\Output;
use Descom\Ai\Bedrock\Messages\Response\Output\Message;
use Descom\Ai\Tests\TestCase;

final class OutputTest extends TestCase
{
    public function test_from_array_hydrates_message(): void
    {
        $output = Output::fromArray([
            'message' => [
                'role' => 'assistant',
                'content' => [['text' => 'hello']],
            ],
        ]);

        $this->assertInstanceOf(Message::class, $output->message);
        $this->assertSame('hello', $output->message->text());
    }
}
