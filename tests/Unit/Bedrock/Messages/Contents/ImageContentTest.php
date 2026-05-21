<?php

declare(strict_types=1);

namespace Descom\Ai\Tests\Unit\Bedrock\Messages\Contents;

use Descom\Ai\Bedrock\Messages\Contents\ImageContent;
use Descom\Ai\Bedrock\Messages\Contents\Sources\BinarySource;
use Descom\Ai\Bedrock\Messages\Contents\Sources\S3Source;
use Descom\Ai\Tests\TestCase;

final class ImageContentTest extends TestCase
{
    public function test_constructor_accepts_valid_format(): void
    {
        $image = new ImageContent('jpeg', new BinarySource('bytes'));

        $this->assertSame('jpeg', $image->format);
        $this->assertInstanceOf(BinarySource::class, $image->source);
    }

    public function test_constructor_rejects_invalid_format(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new ImageContent('tiff', new BinarySource('bytes'));
    }

    public function test_to_array_with_binary_source(): void
    {
        $image = new ImageContent('png', new BinarySource('raw'));

        $this->assertSame([
            'image' => [
                'format' => 'png',
                'source' => ['bytes' => 'raw'],
            ],
        ], $image->toArray());
    }

    public function test_to_array_with_s3_source(): void
    {
        $image = new ImageContent('webp', new S3Source('s3://bucket/img.webp'));

        $this->assertSame([
            'image' => [
                'format' => 'webp',
                'source' => ['uri' => 's3://bucket/img.webp'],
            ],
        ], $image->toArray());
    }
}
