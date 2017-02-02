<?php namespace Cviebrock\ImageValidator\Test;

use Cviebrock\ImageValidator\ImageValidatorServiceProvider;
use Orchestra\Testbench\TestCase;


class ValidateServiceProviderTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [
            ImageValidatorServiceProvider::class,
        ];
    }

    /**
     * Bootstraps Laravel application. Tests service providers work on a basic level.
     *
     * @return void
     */
    public function testBootstrap()
    {
        // If we get to here, then all is well!
        $this->assertTrue(true);
    }
}
