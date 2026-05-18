<?php

namespace Descom\AwsBedrock\Tests;

use Descom\AwsBedrock\AwsBedrockServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            AwsBedrockServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Setup default environment
    }
}
