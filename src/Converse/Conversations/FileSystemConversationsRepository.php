<?php

declare(strict_types=1);

namespace Descom\AwsBedrock\Converse\Conversations;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

final class FileSystemConversationsRepository extends ConversationsRepository
{
    public function __construct(
        private readonly string $directory,
        private readonly ?string $disk = null,
    ) {}

    public function create(): ConversationRepository
    {
        $identifier = Str::ulid()->toBase32();

        $this->filesystem()->put($this->path($identifier), '[]');

        return new FileSystemConversationRepository($identifier, $this->directory, $this->disk);
    }

    public function find(string $identifier): ConversationRepository
    {
        if (! $this->filesystem()->exists($this->path($identifier))) {
            throw new RuntimeException("Conversation [{$identifier}] not found.");
        }

        return new FileSystemConversationRepository($identifier, $this->directory, $this->disk);
    }

    private function path(string $identifier): string
    {
        return trim($this->directory, '/').'/'.$identifier.'.json';
    }

    private function filesystem(): Filesystem
    {
        return $this->disk !== null ? Storage::disk($this->disk) : Storage::disk();
    }
}
