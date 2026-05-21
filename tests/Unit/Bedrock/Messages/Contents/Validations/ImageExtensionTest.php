<?php

declare(strict_types=1);

namespace Descom\Ai\Tests\Unit\Bedrock\Messages\Contents\Validations;

use Descom\Ai\Bedrock\Messages\Contents\Validations\ImageExtension;
use Descom\Ai\Tests\TestCase;

final class ImageExtensionTest extends TestCase
{
    public function test_valid_returns_true_for_supported_extension(): void
    {
        $this->assertTrue(ImageExtension::valid('jpeg'));
        $this->assertTrue(ImageExtension::valid('png'));
    }

    public function test_valid_returns_false_for_unsupported_extension(): void
    {
        $this->assertFalse(ImageExtension::valid('tiff'));
    }

    public function test_cases_is_not_empty(): void
    {
        $this->assertNotEmpty(ImageExtension::cases());
    }
}
