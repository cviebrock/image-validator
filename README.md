# ImageSize Validator Rule For Laravel 4

This package allows you to validate images based on their dimensions.

## Installation

Install the package through [Composer](http://getcomposer.org).

In your `composer.json` file:

```json
{
	"require": {
		"laravel/framework": "4.0.*",
		// ...
		"cviebrock/imagesize-validator": "dev-master"
	}
}
```

Run `composer install` or `composer update` to install the package.

Add the following to your `providers` array in `config/app.php`:

```php
'providers' => array(
	// ...

	'Cviebrock\ImageSizeValidator\ImageSizeValidatorServiceProvider',
),
```

## Usage

Use it like any `Validator` rule:

```php
$rules = array(
	'my_image_field' => 'image_size:<width>[,<height>]',
);
```

The values for _width_ and _height can be integers, or integers with a modifier prefix:

- `300` or `=300` means the dimension must be exactly 300 pixels.
- `<300` means the dimension must be less than 300 pixels
- `<=300` means the dimension must be less than or equal to 300 pixels
- `>300` means the dimension must be greater than 300 pixels
- `>=300` means the dimension must be greater than or equal to 300 pixels
- `200-300` means the dimension must be betweem 200 and 300 pixels (inclusive)
- `*` means the dimension can be any value

If you only pass one value, it's assumed to apply to both dimensions (i.e. a square image with the given dimensions).


## Example

```php

	// logo must be 300px wide by 400px tall

	$rules = array(
		'logo' => 'required|image|image_size:300,400',
	);

	// logo must be less than or equal to 300x300px.

	$rules = array(
		'logo' => 'required|image|image_size:<=300',
	);

	// logo must be 300px wide but can be any height

	$rules = array(
		'logo' => 'required|image|image_size:300,*',
	);

	// logo must be at least 100px tall and 200-300 pixels wide (inclusive)

	$rules = array(
		'logo' => 'required|image|image_size:>=100,200-300',
	);


```


# License

MIT