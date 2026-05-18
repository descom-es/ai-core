<?php

declare(strict_types=1);

namespace Descom\AwsBedrock\Tests\Unit\Converse\Messages\Contents;

use Descom\AwsBedrock\Converse\Messages\Contents\AudioContent;
use Descom\AwsBedrock\Converse\Messages\Contents\Sources\BinarySource;
use Descom\AwsBedrock\Tests\TestCase;

final class AudioContentTest extends TestCase
{
    public function test_constructor_accepts_valid_format(): void
    {
        $audio = new AudioContent('mp3', new BinarySource('bytes'));

        $this->assertSame('mp3', $audio->format);
    }

    public function test_constructor_rejects_invalid_format(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new AudioContent('aiff', new BinarySource('x'));
    }

    public function test_to_array(): void
    {
        $audio = new AudioContent('wav', new BinarySource('audio-bytes'));

        $this->assertSame([
            'audio' => [
                'format' => 'wav',
                'source' => ['bytes' => 'audio-bytes'],
            ],
        ], $audio->toArray());
    }
}
