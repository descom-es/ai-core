<?php

declare(strict_types=1);

namespace Descom\AwsBedrock\Tests\Unit\Converse\Messages\Contents\Sources;

use Descom\AwsBedrock\Converse\Messages\Contents\Sources\S3Source;
use Descom\AwsBedrock\Tests\TestCase;

final class S3SourceTest extends TestCase
{
    public function test_constructor_preserves_uri(): void
    {
        $source = new S3Source('s3://bucket/key');

        $this->assertSame('s3://bucket/key', $source->uri);
    }

    public function test_with_account_id_returns_self(): void
    {
        $source = new S3Source('s3://bucket/key');

        $this->assertSame($source, $source->withAccountId('123456789012'));
    }

    public function test_to_array_without_account_id(): void
    {
        $source = new S3Source('s3://bucket/key');

        $this->assertSame(['uri' => 's3://bucket/key'], $source->toArray());
    }

    public function test_to_array_with_account_id(): void
    {
        $source = (new S3Source('s3://bucket/key'))->withAccountId('123456789012');

        $this->assertSame(
            ['uri' => 's3://bucket/key', 'accountId' => '123456789012'],
            $source->toArray(),
        );
    }
}
