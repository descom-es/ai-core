<?php

declare(strict_types=1);

namespace Descom\Ai\Tests\Unit\Bedrock\Messages\Contents;

use Descom\Ai\Bedrock\Messages\Contents\DocumentContent;
use Descom\Ai\Bedrock\Messages\Contents\Sources\BinarySource;
use Descom\Ai\Tests\TestCase;

final class DocumentContentTest extends TestCase
{
    public function test_constructor_accepts_valid_format(): void
    {
        $doc = new DocumentContent('pdf', 'report', new BinarySource('bytes'));

        $this->assertSame('pdf', $doc->format);
        $this->assertSame('report', $doc->name);
    }

    public function test_constructor_rejects_invalid_format(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new DocumentContent('exe', 'malware', new BinarySource('x'));
    }

    public function test_to_array_includes_name(): void
    {
        $doc = new DocumentContent('csv', 'sales', new BinarySource('a,b'));

        $this->assertSame([
            'document' => [
                'format' => 'csv',
                'name' => 'sales',
                'source' => ['bytes' => 'a,b'],
            ],
        ], $doc->toArray());
    }
}
