<?php

declare(strict_types=1);

namespace Descom\Ai\Tests\Unit\Bedrock\Messages\Contents;

use Descom\Ai\Bedrock\Messages\Contents\AudioContent;
use Descom\Ai\Bedrock\Messages\Contents\Content;
use Descom\Ai\Bedrock\Messages\Contents\DocumentContent;
use Descom\Ai\Bedrock\Messages\Contents\ImageContent;
use Descom\Ai\Bedrock\Messages\Contents\Sources\BinarySource;
use Descom\Ai\Bedrock\Messages\Contents\Sources\S3Source;
use Descom\Ai\Bedrock\Messages\Contents\TextContent;
use Descom\Ai\Tests\TestCase;

final class ContentTest extends TestCase
{
    public function test_from_array_text(): void
    {
        $content = Content::fromArray(['text' => 'hello']);

        $this->assertInstanceOf(TextContent::class, $content);
        $this->assertSame('hello', $content->text);
    }

    public function test_from_array_image_with_binary_source(): void
    {
        $content = Content::fromArray([
            'image' => [
                'format' => 'png',
                'source' => ['bytes' => 'raw'],
            ],
        ]);

        $this->assertInstanceOf(ImageContent::class, $content);
        $this->assertSame('png', $content->format);
        $this->assertInstanceOf(BinarySource::class, $content->source);
    }

    public function test_from_array_document_with_s3_source(): void
    {
        $content = Content::fromArray([
            'document' => [
                'format' => 'pdf',
                'name' => 'paper',
                'source' => ['uri' => 's3://bucket/paper.pdf'],
            ],
        ]);

        $this->assertInstanceOf(DocumentContent::class, $content);
        $this->assertSame('paper', $content->name);
        $this->assertInstanceOf(S3Source::class, $content->source);
    }

    public function test_from_array_audio(): void
    {
        $content = Content::fromArray([
            'audio' => [
                'format' => 'mp3',
                'source' => ['bytes' => 'audio'],
            ],
        ]);

        $this->assertInstanceOf(AudioContent::class, $content);
    }

    public function test_from_array_with_unknown_key_throws(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Content::fromArray(['unknown' => 'value']);
    }
}
