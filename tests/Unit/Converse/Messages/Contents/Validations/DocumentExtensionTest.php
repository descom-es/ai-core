<?php

declare(strict_types=1);

namespace Descom\AwsBedrock\Tests\Unit\Converse\Messages\Contents\Validations;

use Descom\AwsBedrock\Converse\Messages\Contents\Validations\DocumentExtension;
use Descom\AwsBedrock\Tests\TestCase;

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
