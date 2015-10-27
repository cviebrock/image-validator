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
        
        protected $translationLocation = 'image-validator';

        protected $translationLocationExtra = '::validation';


	/**
	* Bootstrap the application events.
	*
	* @return void
	*/
	public function boot()
	{
                $translationCheck = trans($this->translationLocation);
                if(!is_array($translationCheck)) {
                    $this->loadTranslationsFrom(__DIR__.'/../lang', $this->translationLocation);
                    $this->translationLocation = $this->translationLocation . $this->translationLocationExtra;
                }

		$this->app->bind('Cviebrock\ImageValidator\ImageValidator', function($app)
		{
			$validator = new ImageValidator($app['translator'], [], [], trans($this->translationLocation), array(), $this->translationLocation );

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
	* Returns the translation string depending on laravel version
	* @return string
		*/
	protected function loadTranslator()
	{
		return trans($this->translationLocation);
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
		$translation = trans($this->translationLocation);
		$this->app['validator']->extend($rule, 'Cviebrock\ImageValidator\ImageValidator@validate' . $method, $translation[$rule]);
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
