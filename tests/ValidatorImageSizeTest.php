<?php

use Cviebrock\ImageSizeValidator\ImageSizeValidator;

class ValidatorImageSizeTest extends PHPUnit_Framework_TestCase
{
	protected $translator;
	protected $data;
	protected $rules;
	protected $messages;


	public function setUp()
	{
		$this->translator = Mockery::mock('Symfony\Component\Translation\TranslatorInterface');
		$this->translator->shouldReceive('trans');
		$this->data = array(
			'image' => dirname(__FILE__) . '/images/200x250.png'
		);
	}


	public function tearDown()
	{
		Mockery::close();
	}


	public function testValidatesMatch()
	{

		$validator = new ImageSizeValidator(
			$this->translator,
			$this->data,
			array( 'image' => 'image_size:200,250' ),
			$this->messages
		);

		$this->assertTrue($validator->passes());
	}

	public function testValidatesSquare()
	{

		$validator = new ImageSizeValidator(
			$this->translator,
			$this->data,
			array( 'image' => 'image_size:200' ),
			$this->messages
		);

		$this->assertTrue($validator->fails());
	}

	public function testValidatesLessThan()
	{

		$validator = new ImageSizeValidator(
			$this->translator,
			$this->data,
			array( 'image' => 'image_size:<200,<250' ),
			$this->messages
		);

		$this->assertTrue($validator->fails());
	}

	public function testValidatesLessThanEqual()
	{

		$validator = new ImageSizeValidator(
			$this->translator,
			$this->data,
			array( 'image' => 'image_size:<=200,<=250' ),
			$this->messages
		);

		$this->assertTrue($validator->passes());
	}

	public function testValidatesGreaterThan()
	{

		$validator = new ImageSizeValidator(
			$this->translator,
			$this->data,
			array( 'image' => 'image_size:>200,>250' ),
			$this->messages
		);

		$this->assertTrue($validator->fails());
	}

	public function testValidatesGreaterThanEqual()
	{

		$validator = new ImageSizeValidator(
			$this->translator,
			$this->data,
			array( 'image' => 'image_size:>=200,>=250' ),
			$this->messages
		);

		$this->assertTrue($validator->passes());
	}

	public function testValidatesAnySize()
	{

		$validator = new ImageSizeValidator(
			$this->translator,
			$this->data,
			array( 'image' => 'image_size:*,250' ),
			$this->messages
		);

		$this->assertTrue($validator->passes());
	}

	public function testValidatesRange()
	{

		$validator = new ImageSizeValidator(
			$this->translator,
			$this->data,
			array( 'image' => 'image_size:200-300' ),
			$this->messages
		);

		$this->assertTrue($validator->passes());
	}

}