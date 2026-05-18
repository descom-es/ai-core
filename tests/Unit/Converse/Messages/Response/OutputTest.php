<?php

declare(strict_types=1);

namespace Descom\AwsBedrock\Tests\Unit\Converse\Messages\Response;

use Descom\AwsBedrock\Converse\Messages\Response\Output;
use Descom\AwsBedrock\Converse\Messages\Response\Output\Message;
use Descom\AwsBedrock\Tests\TestCase;

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
