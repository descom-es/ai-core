<?php

declare(strict_types=1);

namespace Descom\Ai\Bedrock\Converse\Conversations;

use Descom\Ai\Bedrock\Messages\Message;
use Descom\Ai\Bedrock\Messages\Messages;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

final class FileSystemConversationRepository extends ConversationRepository
{
    public function __construct(
        string $identifier,
        private readonly string $directory,
        private readonly ?string $disk = null,
    ) {
        parent::__construct($identifier);
    }

    public function messages(): Messages
    {
        $payload = $this->read();

        $messages = new Messages;

        foreach ($payload as $messageData) {
            $messages->addMessage(Message::fromArray(self::decodeBinaryBytes($messageData)));
        }

        return $messages;
    }

    public function addMessage(Message $message)
    {
        $payload = $this->read();
        $payload[] = self::encodeBinaryBytes($message->toArray());

        $this->write($payload);
    }

    public function close(): bool
    {
        return $this->filesystem()->delete($this->path());
    }

    private function path(): string
    {
        return trim($this->directory, '/').'/'.$this->identifier.'.json';
    }

    private function filesystem(): Filesystem
    {
        return $this->disk !== null ? Storage::disk($this->disk) : Storage::disk();
    }

    private function read(): array
    {
        $contents = $this->filesystem()->get($this->path());

        if ($contents === null || $contents === '') {
            return [];
        }

        return json_decode($contents, true, flags: JSON_THROW_ON_ERROR);
    }

    private function write(array $payload): void
    {
        $this->filesystem()->put(
            $this->path(),
            json_encode($payload, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        );
    }

    private static function encodeBinaryBytes(array $node): array
    {
        return self::walkBytes($node, base64_encode(...));
    }

    private static function decodeBinaryBytes(array $node): array
    {
        return self::walkBytes($node, base64_decode(...));
    }

    private static function walkBytes(array $node, callable $transform): array
    {
        foreach ($node as $key => $value) {
            if ($key === 'bytes' && is_string($value)) {
                $node[$key] = $transform($value);

                continue;
            }

            if (is_array($value)) {
                $node[$key] = self::walkBytes($value, $transform);
            }
        }

        return $node;
    }
}
