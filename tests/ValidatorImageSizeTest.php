<?php namespace Cviebrock\ImageValidator\Test;

use Cviebrock\ImageValidator\ImageValidator;
use Illuminate\Contracts\Translation\Translator;
use Mockery;
use PHPUnit_Framework_TestCase;


class ValidatorImageSizeTest extends PHPUnit_Framework_TestCase
{

    protected $translator;

    protected $data;

    protected $rules;

    public function setUp()
    {
        $this->translator = Mockery::mock(Translator::class);
        $this->translator->shouldReceive('trans');
        $this->data = [
            'image' => __DIR__ . '/images/200x250.png',
        ];
    }

    public function tearDown()
    {
        Mockery::close();
    }

    public function testValidatesMatch()
    {
        $validator = new ImageValidator(
            $this->translator,
            $this->data,
            ['image' => 'image_size:200,250']
        );

        $this->assertTrue($validator->passes());
    }

    public function testValidatesSquare()
    {
        $validator = new ImageValidator(
            $this->translator,
            $this->data,
            ['image' => 'image_size:200']
        );

        $this->assertTrue($validator->fails());
    }

    public function testValidatesLessThan()
    {
        $validator = new ImageValidator(
            $this->translator,
            $this->data,
            ['image' => 'image_size:<200,<250']
        );

        $this->assertTrue($validator->fails());
    }

    public function testValidatesLessThanEqual()
    {
        $validator = new ImageValidator(
            $this->translator,
            $this->data,
            ['image' => 'image_size:<=200,<=250']
        );

        $this->assertTrue($validator->passes());
    }

    public function testValidatesGreaterThan()
    {
        $validator = new ImageValidator(
            $this->translator,
            $this->data,
            ['image' => 'image_size:>200,>250']
        );

        $this->assertTrue($validator->fails());
    }

    public function testValidatesGreaterThanEqual()
    {
        $validator = new ImageValidator(
            $this->translator,
            $this->data,
            ['image' => 'image_size:>=200,>=250']
        );

        $this->assertTrue($validator->passes());
    }

    public function testValidatesAnySize()
    {
        $validator = new ImageValidator(
            $this->translator,
            $this->data,
            ['image' => 'image_size:*,250']
        );

        $this->assertTrue($validator->passes());
    }

    public function testValidatesRange()
    {
        $validator = new ImageValidator(
            $this->translator,
            $this->data,
            ['image' => 'image_size:200-300']
        );

        $this->assertTrue($validator->passes());
    }
}
