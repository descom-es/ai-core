<?php

declare(strict_types=1);

namespace Descom\AwsBedrock\Tests\Unit\Converse\Messages\Contents\Validations;

use Descom\AwsBedrock\Converse\Messages\Contents\Validations\AudioExtension;
use Descom\AwsBedrock\Tests\TestCase;

final class AudioExtensionTest extends TestCase
{
    public function test_valid_returns_true_for_supported_extension(): void
    {
        $this->assertTrue(AudioExtension::valid('mp3'));
        $this->assertTrue(AudioExtension::valid('wav'));
        $this->assertTrue(AudioExtension::valid('webm'));
    }

    public function test_valid_returns_false_for_unsupported_extension(): void
    {
        $this->assertFalse(AudioExtension::valid('aiff'));
    }

    public function test_cases_is_not_empty(): void
    {
        $this->assertNotEmpty(AudioExtension::cases());
    }
}
