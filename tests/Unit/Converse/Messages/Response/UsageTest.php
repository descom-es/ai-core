<?php

declare(strict_types=1);

namespace Descom\AwsBedrock\Tests\Unit\Converse\Messages\Response;

use Descom\AwsBedrock\Converse\Messages\Response\Usage;
use Descom\AwsBedrock\Tests\TestCase;

final class UsageTest extends TestCase
{
    public function test_constructor_preserves_required_tokens(): void
    {
        $usage = new Usage(inputTokens: 10, outputTokens: 20, totalTokens: 30);

        $this->assertSame(10, $usage->inputTokens);
        $this->assertSame(20, $usage->outputTokens);
        $this->assertSame(30, $usage->totalTokens);
        $this->assertNull($usage->cacheReadInputTokens);
        $this->assertNull($usage->cacheWriteInputTokens);
    }

    public function test_constructor_preserves_optional_cache_tokens(): void
    {
        $usage = new Usage(
            inputTokens: 10,
            outputTokens: 20,
            totalTokens: 30,
            cacheReadInputTokens: 5,
            cacheWriteInputTokens: 7,
        );

        $this->assertSame(5, $usage->cacheReadInputTokens);
        $this->assertSame(7, $usage->cacheWriteInputTokens);
    }

    public function test_from_array_with_full_data(): void
    {
        $usage = Usage::fromArray([
            'inputTokens' => 100,
            'outputTokens' => 200,
            'totalTokens' => 300,
            'cacheReadInputTokens' => 50,
            'cacheWriteInputTokens' => 60,
        ]);

        $this->assertSame(100, $usage->inputTokens);
        $this->assertSame(50, $usage->cacheReadInputTokens);
        $this->assertSame(60, $usage->cacheWriteInputTokens);
    }

    public function test_from_array_without_cache_fields(): void
    {
        $usage = Usage::fromArray([
            'inputTokens' => 1,
            'outputTokens' => 2,
            'totalTokens' => 3,
        ]);

        $this->assertNull($usage->cacheReadInputTokens);
        $this->assertNull($usage->cacheWriteInputTokens);
    }
}
