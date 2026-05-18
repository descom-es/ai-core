<?php

declare(strict_types=1);

namespace Descom\AwsBedrock\Tests\Unit\Converse\Messages\Contents\Sources;

use Descom\AwsBedrock\Converse\Messages\Contents\Sources\BinarySource;
use Descom\AwsBedrock\Converse\Messages\Contents\Sources\S3Source;
use Descom\AwsBedrock\Converse\Messages\Contents\Sources\Source;
use Descom\AwsBedrock\Tests\TestCase;

final class SourceTest extends TestCase
{
    public function test_from_array_with_uri_returns_s3_source(): void
    {
        $source = Source::fromArray(['uri' => 's3://bucket/key']);

        $this->assertInstanceOf(S3Source::class, $source);
        $this->assertSame('s3://bucket/key', $source->uri);
    }

    public function test_from_array_with_uri_and_account_id_applies_account_id(): void
    {
        $source = Source::fromArray(['uri' => 's3://bucket/key', 'accountId' => '123']);

        $this->assertInstanceOf(S3Source::class, $source);
        $this->assertSame(
            ['uri' => 's3://bucket/key', 'accountId' => '123'],
            $source->toArray(),
        );
    }

    public function test_from_array_with_bytes_returns_binary_source(): void
    {
        $source = Source::fromArray(['bytes' => 'raw']);

        $this->assertInstanceOf(BinarySource::class, $source);
        $this->assertSame('raw', $source->bytes);
    }

    public function test_from_array_with_unknown_key_throws(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Source::fromArray(['unknown' => 'value']);
    }
}
