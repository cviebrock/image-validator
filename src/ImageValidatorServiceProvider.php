<?php namespace Cviebrock\ImageValidator;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Factory;

class ImageValidatorServiceProvider extends ServiceProvider
{

	/**
	* Indicates if loading of the provider is deferred.
	*
	* @var bool
	*/
	protected $defer = false;

	protected $rules = array(
		'image_size',
		'image_aspect',
	);


	/**
	* Bootstrap the application events.
	*
	* @return void
	*/
	public function boot()
	{
		$this->loadTranslationsFrom(__DIR__.'/../lang', 'image-validator');
		
		$messages = trans('image-validator::validation');

		$this->app->bind('Cviebrock\ImageValidator\ImageValidator', function($app) use ($messages)
		{
			$validator = new ImageValidator($app['translator'], [], [], $messages );

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
		$method = studly_case($rule);
		$translation = $this->app['translator']->trans('image-validator::validation.'.$rule);
		$this->app['validator']->extend($rule, 'Cviebrock\ImageValidator\ImageValidator@validate' . $method, $translation);
		$this->app['validator']->replacer($rule, 'Cviebrock\ImageValidator\ImageValidator@replace' . $method );
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
		return [];
	}

}
