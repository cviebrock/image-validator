<?php namespace Cviebrock\ImageSizeValidator;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Factory;

class ImageSizeValidatorServiceProvider extends ServiceProvider
{
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	protected $rules = array(
		'image_size'
	);


	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('cviebrock/imagesize-validator');

		// $app = $this->app;

		$this->app->bind('Cviebrock\ImageSizeValidator\ImageSizeValidator', function($app)
		{
			$validator = new ImageSizeValidator($app['translator'], array(), array(), $app['translator']->get('imagesize-validator::validation'));

			if (isset($app['validation.presence']))
			{
				$validator->setPresenceVerifier($app['validation.presence']);
			}

			return $validator;

		});

		$this->addNewRules();
	}


	/**
	 * Get the list of new rules being added to the validator.
	 * @return array
	 */
	public function getRules()
	{
		return $this->rules;
	}


	/**
	 * Add new rules to the validator.
	 */
	protected function addNewRules()
	{
		foreach($this->getRules() as $rule)
		{
			$this->extendValidator($rule);
		}
	}


	/**
	 * Extend the validator with new rules.
	 * @param  string $rule
	 * @return void
	 */
	protected function extendValidator($rule)
	{
		$method = 'validate' . studly_case($rule);
		$translation = $this->app['translator']->get('imagesize-validator');
		$this->app['validator']->extend($rule, 'Cviebrock\ImageSizeValidator\ImageSizeValidator@' . $method, $translation[$rule]);
	}


	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
	}


	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}