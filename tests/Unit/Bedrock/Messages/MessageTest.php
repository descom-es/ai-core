<?php

declare(strict_types=1);

namespace Descom\Ai\Tests\Unit\Bedrock\Messages;

use Descom\Ai\Bedrock\Messages\Contents\Contents;
use Descom\Ai\Bedrock\Messages\Contents\DocumentContent;
use Descom\Ai\Bedrock\Messages\Contents\ImageContent;
use Descom\Ai\Bedrock\Messages\Contents\Sources\BinarySource;
use Descom\Ai\Bedrock\Messages\Contents\Sources\S3Source;
use Descom\Ai\Bedrock\Messages\Contents\TextContent;
use Descom\Ai\Bedrock\Messages\Message;
use Descom\Ai\Bedrock\Messages\Role;
use Descom\Ai\Tests\TestCase;

final class MessageTest extends TestCase
{
    public function test_constructor_preserves_role_and_contents(): void
    {
        $contents = new Contents([new TextContent('hi')]);
        $message = new Message(Role::USER, $contents);

        $this->assertSame(Role::USER, $message->role);
        $this->assertSame($contents, $message->contents);
    }

    public function test_to_array_with_text_content(): void
    {
        $message = new Message(Role::USER, new Contents([new TextContent('hi')]));

        $this->assertSame([
            'role' => 'user',
            'content' => [
                ['text' => 'hi'],
            ],
        ], $message->toArray());
    }

    public function test_from_array_reconstructs_message(): void
    {
        $message = Message::fromArray([
            'role' => 'assistant',
            'content' => [['text' => 'hello']],
        ]);

        $this->assertSame(Role::ASSISTANT, $message->role);
        $this->assertCount(1, $message->contents);
        $this->assertInstanceOf(TextContent::class, $message->contents->first());
    }

    public function test_roundtrip_text(): void
    {
        $original = new Message(Role::USER, new Contents([new TextContent('roundtrip')]));

        $restored = Message::fromArray($original->toArray());

        $this->assertEquals($original->toArray(), $restored->toArray());
    }

    public function test_roundtrip_image_with_binary_source(): void
    {
        $original = new Message(
            Role::USER,
            new Contents([new ImageContent('png', new BinarySource('raw'))]),
        );

        $restored = Message::fromArray($original->toArray());

        $this->assertEquals($original->toArray(), $restored->toArray());
    }

    public function test_roundtrip_document_with_s3_source(): void
    {
        $original = new Message(
            Role::USER,
            new Contents([
                new DocumentContent('pdf', 'doc', new S3Source('s3://bucket/doc.pdf')),
            ]),
        );

        $restored = Message::fromArray($original->toArray());

        $this->assertEquals($original->toArray(), $restored->toArray());
    }
}
