<?php

declare(strict_types=1);

namespace Descom\AwsBedrock\Tests\Unit\Converse\Messages;

use Descom\AwsBedrock\Converse\Messages\Role;
use Descom\AwsBedrock\Tests\TestCase;

final class RoleTest extends TestCase
{
    public function test_from_user(): void
    {
        $this->assertSame(Role::USER, Role::from('user'));
    }

    public function test_from_assistant(): void
    {
        $this->assertSame(Role::ASSISTANT, Role::from('assistant'));
    }

    public function test_from_invalid_throws(): void
    {
        $this->expectException(\ValueError::class);

        Role::from('invalid');
    }
}
