<?php

declare(strict_types=1);

namespace Descom\Ai\Tests\Unit\Bedrock\Messages\Contents\Sources;

use Descom\Ai\Bedrock\Messages\Contents\Sources\BinarySource;
use Descom\Ai\Tests\TestCase;

final class BinarySourceTest extends TestCase
{
    public function test_constructor_preserves_bytes(): void
    {
        $source = new BinarySource('raw-bytes');

        $this->assertSame('raw-bytes', $source->bytes);
    }

    public function test_to_array(): void
    {
        $source = new BinarySource('raw-bytes');

        $this->assertSame(['bytes' => 'raw-bytes'], $source->toArray());
    }
}
