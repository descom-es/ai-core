<?php

declare(strict_types=1);

namespace Descom\Ai\Tests\Feature\Bedrock\Converse\Conversations;

use Descom\Ai\Bedrock\Converse\Conversations\FileSystemConversationRepository;
use Descom\Ai\Bedrock\Converse\Conversations\FileSystemConversationsRepository;
use Descom\Ai\Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

final class FileSystemConversationsRepositoryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
    }

    public function test_create_persists_empty_array_and_returns_repository(): void
    {
        $repo = new FileSystemConversationsRepository('conversations');

        $conversation = $repo->create();

        $this->assertInstanceOf(FileSystemConversationRepository::class, $conversation);
        $identifier = $conversation->identifier;
        $this->assertMatchesRegularExpression('/^[0-9A-HJKMNP-TV-Z]{26}$/i', $identifier);
        $this->assertSame('[]', Storage::disk('local')->get("conversations/{$identifier}.json"));
    }

    public function test_find_returns_repository_for_existing_conversation(): void
    {
        $repo = new FileSystemConversationsRepository('conversations');
        $created = $repo->create();

        $found = $repo->find($created->identifier);

        $this->assertInstanceOf(FileSystemConversationRepository::class, $found);
        $this->assertSame($created->identifier, $found->identifier);
    }

    public function test_find_throws_when_conversation_missing(): void
    {
        $repo = new FileSystemConversationsRepository('conversations');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Conversation [does-not-exist] not found.');

        $repo->find('does-not-exist');
    }
}
