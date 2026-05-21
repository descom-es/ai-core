<?php

declare(strict_types=1);

namespace Descom\Ai\Tests\Feature\Bedrock\Converse\Conversations;

use Descom\Ai\Bedrock\Converse\Conversations\FileSystemConversationRepository;
use Descom\Ai\Bedrock\Messages\Contents\Contents;
use Descom\Ai\Bedrock\Messages\Contents\ImageContent;
use Descom\Ai\Bedrock\Messages\Contents\Sources\BinarySource;
use Descom\Ai\Bedrock\Messages\Contents\TextContent;
use Descom\Ai\Bedrock\Messages\Message;
use Descom\Ai\Bedrock\Messages\Messages;
use Descom\Ai\Bedrock\Messages\Role;
use Descom\Ai\Tests\TestCase;
use Illuminate\Support\Facades\Storage;

final class FileSystemConversationRepositoryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
    }

    public function test_add_message_writes_json_payload(): void
    {
        $repo = new FileSystemConversationRepository('conv-1', 'conversations');

        $repo->addMessage(new Message(Role::USER, new Contents([new TextContent('hi')])));

        Storage::disk('local')->assertExists('conversations/conv-1.json');
        $this->assertSame(
            [['role' => 'user', 'content' => [['text' => 'hi']]]],
            json_decode(Storage::disk('local')->get('conversations/conv-1.json'), true),
        );
    }

    public function test_messages_rehydrates_in_order(): void
    {
        $repo = new FileSystemConversationRepository('conv-2', 'conversations');
        $repo->addMessage(new Message(Role::USER, new Contents([new TextContent('one')])));
        $repo->addMessage(new Message(Role::ASSISTANT, new Contents([new TextContent('two')])));

        $messages = $repo->messages();

        $this->assertCount(2, $messages);
        $this->assertSame(Role::USER, $messages->all()[0]->role);
        $this->assertSame(Role::ASSISTANT, $messages->all()[1]->role);
    }

    public function test_roundtrip_binary_bytes_uses_base64_on_disk_and_decodes_on_read(): void
    {
        $repo = new FileSystemConversationRepository('conv-3', 'conversations');
        $raw = "\x00\x01\xFF\x7F";

        $repo->addMessage(new Message(
            Role::USER,
            new Contents([new ImageContent('png', new BinarySource($raw))]),
        ));

        // On disk: bytes are base64-encoded.
        $onDisk = json_decode(Storage::disk('local')->get('conversations/conv-3.json'), true);
        $this->assertSame(base64_encode($raw), $onDisk[0]['content'][0]['image']['source']['bytes']);

        // On read: bytes are decoded back to raw.
        $restored = $repo->messages()->all()[0];
        $imageContent = $restored->contents->all()[0];
        $this->assertSame($raw, $imageContent->source->bytes);
    }

    public function test_add_messages_iterates_and_persists(): void
    {
        $repo = new FileSystemConversationRepository('conv-4', 'conversations');
        $batch = new Messages([
            new Message(Role::USER, new Contents([new TextContent('a')])),
            new Message(Role::USER, new Contents([new TextContent('b')])),
        ]);

        $repo->addMessages($batch);

        $this->assertCount(2, $repo->messages());
    }

    public function test_messages_returns_empty_when_file_does_not_exist(): void
    {
        $repo = new FileSystemConversationRepository('missing', 'conversations');

        $this->assertCount(0, $repo->messages());
    }

    public function test_messages_returns_empty_when_file_is_empty(): void
    {
        Storage::disk('local')->put('conversations/empty.json', '');

        $repo = new FileSystemConversationRepository('empty', 'conversations');

        $this->assertCount(0, $repo->messages());
    }

    public function test_close_deletes_file(): void
    {
        $repo = new FileSystemConversationRepository('to-close', 'conversations');
        $repo->addMessage(new Message(Role::USER, new Contents([new TextContent('bye')])));

        $this->assertTrue($repo->close());
        Storage::disk('local')->assertMissing('conversations/to-close.json');
    }

    public function test_custom_disk_is_used(): void
    {
        Storage::fake('custom');
        $repo = new FileSystemConversationRepository('conv-x', 'conversations', 'custom');

        $repo->addMessage(new Message(Role::USER, new Contents([new TextContent('on-custom')])));

        Storage::disk('custom')->assertExists('conversations/conv-x.json');
        Storage::disk('local')->assertMissing('conversations/conv-x.json');
    }
}
