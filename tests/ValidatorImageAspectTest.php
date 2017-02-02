<?php namespace Cviebrock\ImageValidator\Test;

use Cviebrock\ImageValidator\ImageValidator;
use Illuminate\Contracts\Translation\Translator;
use Mockery;
use PHPUnit_Framework_TestCase;


class ValidatorImageAspectTest extends PHPUnit_Framework_TestCase
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

    public function testValidatesAspect()
    {
        $validator = new ImageValidator(
            $this->translator,
            $this->data,
            ['image' => 'image_aspect:4,5']
        );

        $this->assertTrue($validator->passes());
    }

    public function testValidatesAspectDecimal()
    {
        $validator = new ImageValidator(
            $this->translator,
            $this->data,
            ['image' => 'image_aspect:0.8']
        );

        $this->assertTrue($validator->passes());
    }

    public function testValidatesReverseAspect()
    {
        $validator = new ImageValidator(
            $this->translator,
            $this->data,
            ['image' => 'image_aspect:~5,4']
        );

        $this->assertTrue($validator->passes());
    }

    public function testValidatesReverseAspectDecimal()
    {
        $validator = new ImageValidator(
            $this->translator,
            $this->data,
            ['image' => 'image_aspect:~1.25']
        );

        $this->assertTrue($validator->passes());
    }

    public function testRoundingAspects()
    {
        $validator = new ImageValidator(
            $this->translator,
            ['image' => __DIR__ . '/images/1024x682.png'],
            ['image' => 'image_aspect:3,2']
        );

        $this->assertFalse($validator->passes());

        $validator = new ImageValidator(
            $this->translator,
            ['image' => __DIR__ . '/images/1024x683.png'],
            ['image' => 'image_aspect:3,2']
        );

        $this->assertTrue($validator->passes());
    }
}
