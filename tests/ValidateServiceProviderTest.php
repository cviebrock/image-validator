<?php

use Illuminate\Validation\Factory;
use Illuminate\Support\Str;

class ValidateServiceProviderTest extends PHPUnit_Framework_TestCase {

	public function testBoot()
	{

		$translator = Mockery::mock('Symfony\Component\Translation\TranslatorInterface');
		$translator->shouldReceive('get');

		$presence = Mockery::mock('Illuminate\Validation\PresenceVerifierInterface');

		$factory = new Factory($translator);
		$factory->setPresenceVerifier($presence);

		$container = Mockery::mock('Illuminate\Container\Container');
		$container->shouldReceive('bind');
		$container->shouldReceive('offsetGet')->with('translator')->andReturn($translator);
		$container->shouldReceive('offsetGet')->with('validator')->andReturn($factory);

		$sp = Mockery::mock('Cviebrock\ImageValidator\ImageValidatorServiceProvider[package]', array($container));
		$sp->shouldReceive('package');
		$sp->boot();

		$validator = $factory->make(array(), array());

		foreach ($validator->getExtensions() as $rule => $class_and_method)
		{
			$this->assertTrue(in_array($rule, $sp->getRules()));
			$this->assertEquals('Cviebrock\ImageValidator\ImageValidator@' . 'validate' . studly_case($rule), $class_and_method);

			list($class, $method) = Str::parseCallback($class_and_method, null);

			$this->assertTrue(method_exists($class, $method));
		}

	}

	public function tearDown() {
		Mockery::close();
	}

}
