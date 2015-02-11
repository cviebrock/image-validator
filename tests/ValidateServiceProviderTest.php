<?php

use Orchestra\Testbench\TestCase;

class ValidateServiceProviderTest extends TestCase {

	protected function getPackageProviders($app)
	{
		return [
			'Cviebrock\ImageValidator\ImageValidatorServiceProvider'
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
	}

}
