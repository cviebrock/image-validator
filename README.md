# Image Validator Rules For Laravel 4

Extra Laravel validation rules for dealing with images.

[![Latest Stable Version](https://poser.pugx.org/cviebrock/image-validator/v/stable.png)](https://packagist.org/packages/cviebrock/image-validator)
[![Total Downloads](https://poser.pugx.org/cviebrock/image-validator/downloads.png)](https://packagist.org/packages/cviebrock/image-validator)


* [Installation](#installation)
* [Usage](#usage)
* [Examples](#examples)
* [Bugs, Suggestions and Contributions](#bugs)
* [Copyright and License](#copyright)



<a name="installation"></a>
## Installation

Install the package through [Composer](http://getcomposer.org).

In your `composer.json` file:

```json
{
	"require": {
		"laravel/framework": ">=4.1.21",
		// ...
		"cviebrock/image-validator": "1.0.*"
	}
}
```

**Note:** the minimum version of Laravel that's supported is 4.1.21.  This is when class-based replacers were introduced to the core, allowing packages to extend the validator with classes that also handle custom messages.

Run `composer install` or `composer update` to install the package.

Add the following to your `providers` array in `app/config/app.php`:

```php
'providers' => array(
	// ...

	'Cviebrock\ImageValidator\ImageValidatorServiceProvider',
),
```



<a name="usage"></a>
## Usage

### image_size

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

### image_aspect

```php
$rules = array(
	'my_image_field' => 'image_aspect:<ratio>',
);
```

The value for _ratio_ represents _width ÷ height_ and be either a decimal or two values (width, height; both integers):

- `0.75`
- `3,4`

The value (or first value, if providing height and width) can also be prefixed with a tilde `~` character,
in which case the orientation doesn't matter:

- `~3,4` means the image can have an aspect ratio of either 3:4 or 4:3.

Note that you may run into issues with floating point rounding.


<a name="examples"></a>
## Examples

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

	// logo must be square

	$rules = array(
		'logo' => 'required|image|image_aspect:1',
	);

	// logo must be ready for the big screen TV :)

	$rules = array(
		'logo' => 'required|image|image_aspect:16:9',
	);


```



<a name="bugs"></a>
## Bugs, Suggestions and Contributions

Please use Github for bugs, comments, suggestions.

1. Fork the project.
2. Create your bugfix/feature branch and write your code.
3. Create unit tests for your code:
	- Run `composer install --dev` in the root directory to install required testing packages.
	- Add your test methods to `tests/ValidatorTest.php`.
	- Run `vendor/bin/phpunit` to the new (and all previous) tests and make sure everything passes.
3. Commit your changes (and your tests) and push to your branch.
4. Create a new pull request against the imagesize-validator `develop` branch.

**Please note that you must create your pull request against the `develop` branch.**



<a name="copyright"></a>
## Copyright and License

Eloquent-Sluggable was written by Colin Viebrock and released under the MIT License. See the LICENSE file for details.

Copyright 2013 Colin Viebrock



## Thanks

Lots of thanks to https://bitbucket.org/hampel/validate-laravel for the structure of creating a package to add validator rules to Laravel, and setting up useful unit tests.
