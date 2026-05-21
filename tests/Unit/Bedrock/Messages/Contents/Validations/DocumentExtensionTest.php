<?php

declare(strict_types=1);

namespace Descom\Ai\Tests\Unit\Bedrock\Messages\Contents\Validations;

use Descom\Ai\Bedrock\Messages\Contents\Validations\DocumentExtension;
use Descom\Ai\Tests\TestCase;

final class DocumentExtensionTest extends TestCase
{
    public function test_valid_returns_true_for_supported_extension(): void
    {
        $this->assertTrue(DocumentExtension::valid('pdf'));
        $this->assertTrue(DocumentExtension::valid('csv'));
        $this->assertTrue(DocumentExtension::valid('md'));
    }

    public function test_valid_returns_false_for_unsupported_extension(): void
    {
        $this->assertFalse(DocumentExtension::valid('exe'));
    }

    public function test_cases_is_not_empty(): void
    {
        $this->assertNotEmpty(DocumentExtension::cases());
    }
}
